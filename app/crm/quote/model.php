<?php
class quoteModel extends model
{
    public function getByID($id)
    {
        $quote = $this->dao->select('*')->from(TABLE_QUOTE)->where('id')->eq($id)->fetch();
        if(!$quote) return false;
        $quote->items = $this->dao->select('*')->from(TABLE_QUOTEITEM)->where('quote')->eq($id)->orderBy('sort_asc, id_asc')->fetchAll('id');
        return $quote;
    }

    public function getList($status = '', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_QUOTE)
            ->where('deleted')->eq(0)
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    public function create()
    {
        $now = helper::now();
        $quote = fixer::input('post')
            ->add('status', 'draft')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->setDefault('owner', $this->app->user->account)
            ->remove('productNameList,quantityList,priceList,amountList')
            ->get();

        $this->dao->insert(TABLE_QUOTE)
            ->data($quote)
            ->autoCheck()
            ->batchCheck($this->config->quote->require->create, 'notempty')
            ->exec();

        if(dao::isError()) return false;
        $quoteID = $this->dao->lastInsertID();

        $this->saveItems($quoteID);
        $this->updateTotal($quoteID);
        return $quoteID;
    }

    public function update($quoteID)
    {
        $oldQuote = $this->getByID($quoteID);
        $quote = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('productNameList,quantityList,priceList,amountList')
            ->get();

        $this->dao->update(TABLE_QUOTE)->data($quote)->where('id')->eq($quoteID)->exec();
        if(dao::isError()) return false;

        $this->saveItems($quoteID);
        $this->updateTotal($quoteID);
        return commonModel::createChanges($oldQuote, $quote);
    }

    private function saveItems($quoteID)
    {
        if(empty($_POST['productNameList'])) return;
        $oldItems = $this->dao->select('id')->from(TABLE_QUOTEITEM)->where('quote')->eq($quoteID)->fetchAll('id');
        $savedIDs = array();

        foreach($this->post->productNameList as $key => $productName)
        {
            if(empty($productName)) continue;
            $item = new stdclass();
            $item->quote       = $quoteID;
            $item->productName = $productName;
            $item->product     = isset($_POST['productList'][$key]) ? $_POST['productList'][$key] : 0;
            $item->quantity    = isset($_POST['quantityList'][$key]) ? $_POST['quantityList'][$key] : 0;
            $item->price       = isset($_POST['priceList'][$key]) ? $_POST['priceList'][$key] : 0;
            $item->amount      = $item->quantity * $item->price;
            $item->sort        = $key;

            $existingID = isset($_POST['idList'][$key]) ? $_POST['idList'][$key] : 0;
            if($existingID && isset($oldItems[$existingID]))
            {
                $this->dao->update(TABLE_QUOTEITEM)->data($item)->where('id')->eq($existingID)->exec();
                $savedIDs[$existingID] = $existingID;
            }
            else
            {
                $this->dao->insert(TABLE_QUOTEITEM)->data($item)->exec();
                $savedIDs[$this->dao->lastInsertID()] = $this->dao->lastInsertID();
            }
        }

        foreach($oldItems as $oldID => $old)
        {
            if(!isset($savedIDs[$oldID]))
                $this->dao->delete()->from(TABLE_QUOTEITEM)->where('id')->eq($oldID)->exec();
        }
    }

    public function updateTotal($quoteID)
    {
        $total = $this->dao->select('SUM(amount) as total')->from(TABLE_QUOTEITEM)->where('quote')->eq($quoteID)->fetch();
        $finalAmount = $total->total ?: 0;
        $this->dao->update(TABLE_QUOTE)->set('totalAmount')->eq($finalAmount)->set('finalAmount')->eq($finalAmount)->where('id')->eq($quoteID)->exec();
    }

    public function sendQuote($quoteID)
    {
        $this->dao->update(TABLE_QUOTE)->set('status')->eq('sent')->where('id')->eq($quoteID)->exec();
        return !dao::isError();
    }

    public function accept($quoteID)
    {
        $this->dao->update(TABLE_QUOTE)->set('status')->eq('accepted')->where('id')->eq($quoteID)->exec();
        return !dao::isError();
    }

    public function reject($quoteID)
    {
        $this->dao->update(TABLE_QUOTE)->set('status')->eq('rejected')->where('id')->eq($quoteID)->exec();
        return !dao::isError();
    }
}
