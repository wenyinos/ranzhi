<?php
class purchase extends control
{
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    public function browse($status = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->session->set('purchaseList', $this->app->getURI(true));

        $this->view->title      = $this->lang->purchase->browse;
        $this->view->purchases  = $this->purchase->getList($status, '', $orderBy, $pager);
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->status     = $status;
        $this->display();
    }

    public function create()
    {
        if($_POST)
        {
            $purchaseID = $this->purchase->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('purchase', $purchaseID, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->purchase->create;
        $this->view->providers = $this->loadModel('customer')->getPairs('provider');
        $this->view->products  = $this->loadModel('product')->getPairs();
        $this->view->warehouses = $this->loadModel('warehouse')->getPairs();
        $this->display();
    }

    public function edit($purchaseID)
    {
        if($_POST)
        {
            $changes = $this->purchase->update($purchaseID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('purchase', $purchaseID, 'Edited');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->purchase->edit;
        $this->view->purchase  = $this->purchase->getByID($purchaseID);
        $this->view->providers = $this->loadModel('customer')->getPairs('provider');
        $this->view->products  = $this->loadModel('product')->getPairs();
        $this->display();
    }

    public function view($purchaseID)
    {
        $this->view->title    = $this->lang->purchase->view;
        $this->view->purchase = $this->purchase->getByID($purchaseID);
        $this->view->users    = $this->loadModel('user')->getPairs();
        $this->view->warehouses = $this->loadModel('warehouse')->getPairs();
        $this->display();
    }

    public function submit($purchaseID)
    {
        $this->purchase->submit($purchaseID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->purchase->submitSuccess, 'locate' => inlink('browse')));
    }

    public function approve($purchaseID)
    {
        $this->purchase->approve($purchaseID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->purchase->approveSuccess, 'locate' => inlink('browse')));
    }

    public function reject($purchaseID)
    {
        $this->purchase->reject($purchaseID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->purchase->rejectSuccess, 'locate' => inlink('browse')));
    }

    public function receive($purchaseID)
    {
        if($_POST)
        {
            $warehouseID   = $this->post->warehouse;
            $receiveItems  = $this->post->receiveQty;
            $this->purchase->receive($purchaseID, $warehouseID, $receiveItems);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('purchase', $purchaseID, 'Received');
            $this->send(array('result' => 'success', 'message' => $this->lang->purchase->receiveSuccess, 'locate' => inlink('view', "purchaseID=$purchaseID")));
        }

        $this->view->title     = $this->lang->purchase->receive;
        $this->view->purchase  = $this->purchase->getByID($purchaseID);
        $this->view->warehouses = $this->loadModel('warehouse')->getPairs();
        $this->display();
    }

    public function delete($purchaseID)
    {
        $purchase = $this->purchase->getByID($purchaseID);
        if($purchase->status != 'draft') $this->send(array('result' => 'fail', 'message' => $this->lang->purchase->errorDeleteNotDraft));

        $this->purchase->delete(TABLE_PURCHASE, $purchaseID);
        $this->dao->delete()->from(TABLE_PURCHASEITEM)->where('purchase')->eq($purchaseID)->exec();
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }
}
