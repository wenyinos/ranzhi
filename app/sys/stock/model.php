<?php
class stockModel extends model
{
    /**
     * Get stock list for a warehouse or all warehouses.
     */
    public function getList($warehouse = '', $orderBy = 'product_asc', $pager = null)
    {
        return $this->dao->select('s.*, p.name as productName, p.code as productCode, w.name as warehouseName')
            ->from(TABLE_STOCK)->alias('s')
            ->leftJoin(TABLE_PRODUCT)->alias('p')->on('s.product=p.id')
            ->leftJoin(TABLE_WAREHOUSE)->alias('w')->on('s.warehouse=w.id')
            ->where('s.quantity')->gt(0, true)
            ->beginIF($warehouse)->andWhere('s.warehouse')->eq($warehouse)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get stock for a specific warehouse+product.
     */
    public function getStock($warehouseID, $productID)
    {
        return $this->dao->select('*')->from(TABLE_STOCK)
            ->where('warehouse')->eq($warehouseID)
            ->andWhere('product')->eq($productID)
            ->fetch();
    }

    /**
     * Stock inbound (入库).
     */
    public function inbound($warehouseID, $productID, $qty, $sourceType, $sourceID, $desc = '')
    {
        $stock = $this->getStock($warehouseID, $productID);
        $beforeQty = $stock ? $stock->quantity : 0;
        $afterQty  = $beforeQty + $qty;

        if($stock)
        {
            $this->dao->update(TABLE_STOCK)
                ->set('quantity')->eq($afterQty)
                ->where('id')->eq($stock->id)
                ->exec();
        }
        else
        {
            $this->dao->insert(TABLE_STOCK)
                ->set('warehouse')->eq($warehouseID)
                ->set('product')->eq($productID)
                ->set('quantity')->eq($qty)
                ->exec();
        }

        $this->log($warehouseID, $productID, 'in', $qty, $beforeQty, $afterQty, $sourceType, $sourceID, $desc);
    }

    /**
     * Stock outbound (出库).
     */
    public function outbound($warehouseID, $productID, $qty, $sourceType, $sourceID, $desc = '')
    {
        $stock = $this->getStock($warehouseID, $productID);
        if(!$stock || $stock->quantity < $qty) return false;

        $beforeQty = $stock->quantity;
        $afterQty  = $beforeQty - $qty;

        $this->dao->update(TABLE_STOCK)
            ->set('quantity')->eq($afterQty)
            ->where('id')->eq($stock->id)
            ->exec();

        $this->log($warehouseID, $productID, 'out', $qty, $beforeQty, $afterQty, $sourceType, $sourceID, $desc);
        return true;
    }

    /**
     * Write stock log.
     */
    private function log($warehouseID, $productID, $type, $qty, $beforeQty, $afterQty, $sourceType, $sourceID, $desc)
    {
        $log = new stdclass();
        $log->warehouse    = $warehouseID;
        $log->product      = $productID;
        $log->type         = $type;
        $log->sourceType   = $sourceType;
        $log->sourceId     = $sourceID;
        $log->quantity     = $qty;
        $log->beforeQty    = $beforeQty;
        $log->afterQty     = $afterQty;
        $log->operator     = $this->app->user->account;
        $log->operatedDate = helper::now();
        $log->description  = $desc;

        $this->dao->insert(TABLE_STOCKLOG)->data($log)->exec();
    }

    /**
     * Get stock log list.
     */
    public function getLogList($warehouse = '', $product = '', $type = '', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('l.*, p.name as productName, w.name as warehouseName')
            ->from(TABLE_STOCKLOG)->alias('l')
            ->leftJoin(TABLE_PRODUCT)->alias('p')->on('l.product=p.id')
            ->leftJoin(TABLE_WAREHOUSE)->alias('w')->on('l.warehouse=w.id')
            ->where('1')->eq(1)
            ->beginIF($warehouse)->andWhere('l.warehouse')->eq($warehouse)->fi()
            ->beginIF($product)->andWhere('l.product')->eq($product)->fi()
            ->beginIF($type)->andWhere('l.type')->eq($type)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get stock alerts (低于安全库存).
     */
    public function getAlerts()
    {
        return $this->dao->select('s.*, p.name as productName, w.name as warehouseName')
            ->from(TABLE_STOCK)->alias('s')
            ->leftJoin(TABLE_PRODUCT)->alias('p')->on('s.product=p.id')
            ->leftJoin(TABLE_WAREHOUSE)->alias('w')->on('s.warehouse=w.id')
            ->where('s.safetyStock')->gt(0, true)
            ->andWhere('s.quantity')->lt('s.safetyStock', true)
            ->fetchAll();
    }
}
