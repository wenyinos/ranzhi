<?php
class purchaseModel extends model
{
    public function getByID($id)
    {
        $purchase = $this->dao->select('*')->from(TABLE_PURCHASE)->where('id')->eq($id)->fetch();
        if(!$purchase) return false;
        $purchase->items = $this->dao->select('*')->from(TABLE_PURCHASEITEM)->where('purchase')->eq($id)->orderBy('sort_asc, id_asc')->fetchAll('id');
        return $purchase;
    }

    public function getList($status = '', $provider = '', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_PURCHASE)
            ->where('deleted')->eq(0)
            ->beginIF($status && $status != 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF($provider)->andWhere('provider')->eq($provider)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    public function create()
    {
        $now = helper::now();
        $purchase = fixer::input('post')
            ->add('status', 'draft')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->remove('productNameList,specList,unitList,quantityList,priceList,amountList')
            ->get();

        $this->dao->insert(TABLE_PURCHASE)
            ->data($purchase)
            ->autoCheck()
            ->batchCheck($this->config->purchase->require->create, 'notempty')
            ->exec();

        if(dao::isError()) return false;
        $purchaseID = $this->dao->lastInsertID();

        /* Insert detail items. */
        $this->saveItems($purchaseID);

        $this->updateTotal($purchaseID);
        return $purchaseID;
    }

    public function update($purchaseID)
    {
        $oldPurchase = $this->getByID($purchaseID);
        $purchase = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('productNameList,specList,unitList,quantityList,priceList,amountList')
            ->get();

        $this->dao->update(TABLE_PURCHASE)
            ->data($purchase)
            ->autoCheck()
            ->batchCheck($this->config->purchase->require->edit, 'notempty')
            ->where('id')->eq($purchaseID)
            ->exec();

        if(dao::isError()) return false;

        /* Update detail items: delete removed, insert new, update existing. */
        $this->saveItems($purchaseID);
        $this->updateTotal($purchaseID);

        return commonModel::createChanges($oldPurchase, $purchase);
    }

    /**
     * Save purchase detail items (复用 refund 明细行模式).
     */
    private function saveItems($purchaseID)
    {
        if(empty($_POST['productNameList'])) return;

        $oldItems = $this->dao->select('id')->from(TABLE_PURCHASEITEM)->where('purchase')->eq($purchaseID)->fetchAll('id');
        $savedIDs = array();

        foreach($this->post->productNameList as $key => $productName)
        {
            if(empty($productName)) continue;
            $item = new stdclass();
            $item->purchase    = $purchaseID;
            $item->productName = $productName;
            $item->product     = isset($_POST['productList'][$key]) ? $_POST['productList'][$key] : 0;
            $item->spec        = isset($_POST['specList'][$key]) ? $_POST['specList'][$key] : '';
            $item->unit        = isset($_POST['unitList'][$key]) ? $_POST['unitList'][$key] : '';
            $item->quantity    = isset($_POST['quantityList'][$key]) ? $_POST['quantityList'][$key] : 0;
            $item->price       = isset($_POST['priceList'][$key]) ? $_POST['priceList'][$key] : 0;
            $item->amount      = $item->quantity * $item->price;
            $item->sort        = $key;

            $existingID = isset($_POST['idList'][$key]) ? $_POST['idList'][$key] : 0;
            if($existingID && isset($oldItems[$existingID]))
            {
                $this->dao->update(TABLE_PURCHASEITEM)->data($item)->where('id')->eq($existingID)->exec();
                $savedIDs[$existingID] = $existingID;
            }
            else
            {
                $this->dao->insert(TABLE_PURCHASEITEM)->data($item)->exec();
                $savedIDs[$this->dao->lastInsertID()] = $this->dao->lastInsertID();
            }
        }

        /* Delete removed items. */
        foreach($oldItems as $oldID => $old)
        {
            if(!isset($savedIDs[$oldID]))
            {
                $this->dao->delete()->from(TABLE_PURCHASEITEM)->where('id')->eq($oldID)->exec();
            }
        }
    }

    /**
     * Update purchase total amount.
     */
    public function updateTotal($purchaseID)
    {
        $total = $this->dao->select('SUM(amount) as total')->from(TABLE_PURCHASEITEM)->where('purchase')->eq($purchaseID)->fetch();
        $this->dao->update(TABLE_PURCHASE)->set('totalAmount')->eq($total->total ?: 0)->where('id')->eq($purchaseID)->exec();
    }

    /**
     * Submit for review (复用 leave 审批模式).
     */
    public function submit($purchaseID)
    {
        $this->dao->update(TABLE_PURCHASE)
            ->set('status')->eq('wait')
            ->where('id')->eq($purchaseID)
            ->exec();
        return !dao::isError();
    }

    /**
     * Approve purchase (复用 leave review 方法).
     */
    public function approve($purchaseID)
    {
        $this->dao->update(TABLE_PURCHASE)
            ->set('status')->eq('pass')
            ->set('reviewedBy')->eq($this->app->user->account)
            ->set('reviewedDate')->eq(helper::now())
            ->where('id')->eq($purchaseID)
            ->exec();
        return !dao::isError();
    }

    /**
     * Reject purchase.
     */
    public function reject($purchaseID)
    {
        $this->dao->update(TABLE_PURCHASE)
            ->set('status')->eq('reject')
            ->set('reviewedBy')->eq($this->app->user->account)
            ->set('reviewedDate')->eq(helper::now())
            ->where('id')->eq($purchaseID)
            ->exec();
        return !dao::isError();
    }

    /**
     * Receive (入库) — 联动库存模块.
     */
    public function receive($purchaseID, $warehouseID, $receiveItems)
    {
        $purchase = $this->getByID($purchaseID);
        if(!$purchase) return false;

        $stockModel = $this->loadModel('stock');

        foreach($receiveItems as $itemID => $qty)
        {
            if($qty <= 0) continue;
            if(!isset($purchase->items[$itemID])) continue;

            $item = $purchase->items[$itemID];
            $newReceived = $item->receivedQty + $qty;

            /* Update received quantity. */
            $this->dao->update(TABLE_PURCHASEITEM)
                ->set('receivedQty')->eq($newReceived)
                ->where('id')->eq($itemID)
                ->exec();

            /* Stock inbound. */
            if($item->product > 0)
            {
                $stockModel->inbound($warehouseID, $item->product, $qty, 'purchase', $purchaseID, "采购入库 #{$purchase->code}");
            }
        }

        /* Check if all items fully received. */
        $unreceived = $this->dao->select('COUNT(*) as cnt')
            ->from(TABLE_PURCHASEITEM)
            ->where('purchase')->eq($purchaseID)
            ->andWhere('quantity')->gt('receivedQty', true)
            ->fetch();

        if($unreceived->cnt == 0)
        {
            $this->dao->update(TABLE_PURCHASE)->set('status')->eq('closed')->where('id')->eq($purchaseID)->exec();
        }

        return !dao::isError();
    }
}
