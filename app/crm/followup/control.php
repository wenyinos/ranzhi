<?php
class followup extends control
{
    public function create()
    {
        if($_POST)
        {
            $id = $this->followup->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('timeline', "objectType={$this->post->objectType}&objectId={$this->post->objectId}")));
        }

        $this->view->title = $this->lang->followup->create;
        $this->display();
    }

    public function timeline($objectType, $objectId)
    {
        $this->view->title     = $this->lang->followup->timeline;
        $this->view->followups = $this->followup->getTimeline($objectType, $objectId);
        $this->view->objectType = $objectType;
        $this->view->objectId   = $objectId;
        $this->display();
    }

    public function edit($id)
    {
        if($_POST)
        {
            $this->followup->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        }

        $this->view->title    = $this->lang->followup->edit;
        $this->view->followup = $this->followup->getByID($id);
        $this->display();
    }

    public function delete($id)
    {
        $followup = $this->followup->getByID($id);
        $this->dao->delete()->from(TABLE_FOLLOWUP)->where('id')->eq($id)->exec();
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->send(array('result' => 'success', 'locate' => inlink('timeline', "objectType={$followup->objectType}&objectId={$followup->objectId}")));
    }
}
