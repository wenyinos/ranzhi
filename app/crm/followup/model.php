<?php
class followupModel extends model
{
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_FOLLOWUP)->where('id')->eq($id)->fetch();
    }

    public function getList($objectType = '', $objectId = 0, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_FOLLOWUP)
            ->where(1)
            ->beginIF($objectType)->andWhere('objectType')->eq($objectType)->fi()
            ->beginIF($objectId)->andWhere('objectId')->eq($objectId)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    public function getTimeline($objectType, $objectId)
    {
        return $this->dao->select('*')->from(TABLE_FOLLOWUP)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectId')->eq($objectId)
            ->orderBy('operatedDate_desc')
            ->fetchAll('id');
    }

    public function create()
    {
        $followup = fixer::input('post')
            ->add('operator', $this->app->user->account)
            ->add('operatedDate', helper::now())
            ->get();

        $this->dao->insert(TABLE_FOLLOWUP)->data($followup)->autoCheck()->exec();
        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    public function update($id)
    {
        $followup = fixer::input('post')->get();
        $this->dao->update(TABLE_FOLLOWUP)->data($followup)->where('id')->eq($id)->exec();
        return !dao::isError();
    }

    /**
     * Get today's followup reminders.
     */
    public function getTodayReminders()
    {
        return $this->dao->select('*')->from(TABLE_FOLLOWUP)
            ->where('nextDate')->eq(helper::today())
            ->orderBy('operator_asc')
            ->fetchAll();
    }
}
