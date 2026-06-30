<?php
class quote extends control
{
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    public function browse($status = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title  = $this->lang->quote->browse;
        $this->view->quotes = $this->quote->getList($status, $orderBy, $pager);
        $this->view->pager  = $pager;
        $this->view->orderBy = $orderBy;
        $this->view->status = $status;
        $this->display();
    }

    public function create()
    {
        if($_POST)
        {
            $quoteID = $this->quote->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('quote', $quoteID, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->quote->create;
        $this->view->customers = $this->loadModel('customer')->getPairs();
        $this->view->products  = $this->loadModel('product')->getPairs();
        $this->display();
    }

    public function edit($quoteID)
    {
        if($_POST)
        {
            $changes = $this->quote->update($quoteID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('quote', $quoteID, 'Edited');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->quote->edit;
        $this->view->quote     = $this->quote->getByID($quoteID);
        $this->view->customers = $this->loadModel('customer')->getPairs();
        $this->view->products  = $this->loadModel('product')->getPairs();
        $this->display();
    }

    public function view($quoteID)
    {
        $this->view->title = $this->lang->quote->view;
        $this->view->quote = $this->quote->getByID($quoteID);
        $this->display();
    }

    public function sendQuote($quoteID)
    {
        $this->quote->sendQuote($quoteID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }

    public function accept($quoteID)
    {
        $this->quote->accept($quoteID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }

    public function reject($quoteID)
    {
        $this->quote->reject($quoteID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }

    public function delete($quoteID)
    {
        $this->quote->delete(TABLE_QUOTE, $quoteID);
        $this->dao->delete()->from(TABLE_QUOTEITEM)->where('quote')->eq($quoteID)->exec();
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }
}
