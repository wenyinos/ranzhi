<?php
class stock extends control
{
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    public function browse($warehouse = '', $orderBy = 'product_asc', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->stock->browse;
        $this->view->stocks     = $this->stock->getList($warehouse, $orderBy, $pager);
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->warehouse  = $warehouse;
        $this->view->warehouses = $this->loadModel('warehouse')->getPairs();
        $this->display();
    }

    public function log($warehouse = '', $product = '', $type = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->stock->log;
        $this->view->logs       = $this->stock->getLogList($warehouse, $product, $type, $orderBy, $pager);
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->warehouses = $this->loadModel('warehouse')->getPairs();
        $this->display();
    }

    public function inbound()
    {
        if($_POST)
        {
            $warehouseID = $this->post->warehouse;
            $productID   = $this->post->product;
            $qty         = $this->post->quantity;
            $desc        = $this->post->description;

            $this->stock->inbound($warehouseID, $productID, $qty, 'manual', 0, $desc);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->stock->inboundSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->stock->inbound;
        $this->view->warehouses = $this->loadModel('warehouse')->getPairs();
        $this->view->products   = $this->loadModel('product')->getPairs();
        $this->display();
    }

    public function outbound()
    {
        if($_POST)
        {
            $warehouseID = $this->post->warehouse;
            $productID   = $this->post->product;
            $qty         = $this->post->quantity;
            $desc        = $this->post->description;

            $result = $this->stock->outbound($warehouseID, $productID, $qty, 'manual', 0, $desc);
            if(!$result) $this->send(array('result' => 'fail', 'message' => $this->lang->stock->errorInsufficient));
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->stock->outboundSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title      = $this->lang->stock->outbound;
        $this->view->warehouses = $this->loadModel('warehouse')->getPairs();
        $this->view->products   = $this->loadModel('product')->getPairs();
        $this->display();
    }
}
