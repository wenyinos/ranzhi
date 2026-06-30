<?php
class invoice extends control
{
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    public function browse($type = '', $status = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->session->set('invoiceList', $this->app->getURI(true));

        $this->view->title    = $this->lang->invoice->browse;
        $this->view->invoices = $this->invoice->getList($type, $status, $orderBy, $pager);
        $this->view->pager    = $pager;
        $this->view->orderBy  = $orderBy;
        $this->view->type     = $type;
        $this->view->status   = $status;
        $this->display();
    }

    public function create()
    {
        if($_POST)
        {
            $invoiceID = $this->invoice->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('invoice', $invoiceID, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->invoice->create;
        $this->view->customers = $this->loadModel('customer')->getPairs();
        $this->display();
    }

    public function edit($invoiceID)
    {
        if($_POST)
        {
            $changes = $this->invoice->update($invoiceID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('invoice', $invoiceID, 'Edited');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->invoice->edit;
        $this->view->invoice   = $this->invoice->getByID($invoiceID);
        $this->view->customers = $this->loadModel('customer')->getPairs();
        $this->display();
    }

    public function view($invoiceID)
    {
        $this->view->title   = $this->lang->invoice->view;
        $this->view->invoice = $this->invoice->getByID($invoiceID);
        $this->display();
    }

    public function verify($invoiceID)
    {
        $this->invoice->verify($invoiceID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'message' => $this->lang->invoice->verifySuccess, 'locate' => inlink('browse')));
    }

    public function delete($invoiceID)
    {
        $this->invoice->delete(TABLE_INVOICE, $invoiceID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }
}
