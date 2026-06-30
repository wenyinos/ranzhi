<?php
class opportunity extends control
{
    public function index()
    {
        $this->locate(inlink('browse'));
    }

    public function browse($stage = '', $status = 'open', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title        = $this->lang->opportunity->browse;
        $this->view->opportunities = $this->opportunity->getList($stage, $status, '', $orderBy, $pager);
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->view->stage        = $stage;
        $this->view->status       = $status;
        $this->display();
    }

    public function funnel()
    {
        $this->view->title  = $this->lang->opportunity->funnel;
        $this->view->funnel = $this->opportunity->getFunnel();
        $this->display();
    }

    public function pipeline()
    {
        $this->view->title     = $this->lang->opportunity->pipeline;
        $this->view->pipeline  = $this->opportunity->getPipeline();
        $this->view->stages    = $this->lang->opportunity->stageList;
        $this->display();
    }

    public function create()
    {
        if($_POST)
        {
            $id = $this->opportunity->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('opportunity', $id, 'Created');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title     = $this->lang->opportunity->create;
        $this->view->customers = $this->loadModel('customer')->getPairs();
        $this->display();
    }

    public function edit($id)
    {
        if($_POST)
        {
            $changes = $this->opportunity->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('opportunity', $id, 'Edited');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title       = $this->lang->opportunity->edit;
        $this->view->opportunity = $this->opportunity->getByID($id);
        $this->view->customers   = $this->loadModel('customer')->getPairs();
        $this->display();
    }

    public function view($id)
    {
        $this->view->title       = $this->lang->opportunity->view;
        $this->view->opportunity = $this->opportunity->getByID($id);
        $this->view->users       = $this->loadModel('user')->getPairs();

        $logs = $this->dao->select('*')->from(TABLE_OPPORTUNITYLOG)
            ->where('opportunity')->eq($id)->orderBy('id_desc')->fetchAll();
        $this->view->logs = $logs;
        $this->display();
    }

    public function move($id, $stage)
    {
        $this->opportunity->move($id, $stage);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('pipeline')));
    }

    public function win($id)
    {
        $this->opportunity->win($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('action')->create('opportunity', $id, 'Won');
        $this->send(array('result' => 'success', 'message' => $this->lang->opportunity->winSuccess, 'locate' => inlink('browse')));
    }

    public function lose($id)
    {
        $reason = $this->post->lostReason;
        $this->opportunity->lose($id, $reason);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('action')->create('opportunity', $id, 'Lost');
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }

    public function delete($id)
    {
        $this->opportunity->delete(TABLE_OPPORTUNITY, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('browse')));
    }
}
