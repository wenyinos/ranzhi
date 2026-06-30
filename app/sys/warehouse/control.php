<?php
class warehouse extends control
{
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    public function browse($status = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->session->set('warehouseList', $this->app->getURI(true));

        $this->view->title      = $this->lang->warehouse->browse;
        $this->view->warehouses = $this->warehouse->getList($status, $orderBy, $pager);
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->status     = $status;
        $this->display();
    }

    public function create()
    {
        if($_POST)
        {
            $warehouseID = $this->warehouse->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('warehouse', $warehouseID, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->warehouse->create;
        $this->view->users = $this->loadModel('user')->getPairs();
        $this->display();
    }

    public function edit($warehouseID)
    {
        if($_POST)
        {
            $changes = $this->warehouse->update($warehouseID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('warehouse', $warehouseID, 'Edited');
                if($changes) $this->action->logHistory($actionID, $changes);
            }

            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->warehouse->edit;
        $this->view->warehouse = $this->warehouse->getByID($warehouseID);
        $this->view->users     = $this->loadModel('user')->getPairs();
        $this->display();
    }

    public function view($warehouseID)
    {
        $this->view->title     = $this->lang->warehouse->view;
        $this->view->warehouse = $this->warehouse->getByID($warehouseID);
        $this->display();
    }

    public function delete($warehouseID)
    {
        $this->warehouse->delete(TABLE_WAREHOUSE, $warehouseID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }
}
