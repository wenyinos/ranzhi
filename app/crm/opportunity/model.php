<?php
class opportunityModel extends model
{
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_OPPORTUNITY)->where('id')->eq($id)->fetch();
    }

    public function getList($stage = '', $status = 'open', $owner = '', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_OPPORTUNITY)
            ->where('deleted')->eq(0)
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->beginIF($stage)->andWhere('stage')->eq($stage)->fi()
            ->beginIF($owner)->andWhere('owner')->eq($owner)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    public function create()
    {
        $opportunity = fixer::input('post')
            ->add('status', 'open')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->setDefault('stage', 'initial')
            ->setDefault('owner', $this->app->user->account)
            ->get();

        $this->dao->insert(TABLE_OPPORTUNITY)
            ->data($opportunity)
            ->autoCheck()
            ->batchCheck($this->config->opportunity->require->create, 'notempty')
            ->exec();

        if(dao::isError()) return false;
        $id = $this->dao->lastInsertID();
        $this->logStage($id, '', 'initial');
        return $id;
    }

    public function update($id)
    {
        $old = $this->getByID($id);
        $opportunity = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('status,stage')
            ->get();

        $this->dao->update(TABLE_OPPORTUNITY)
            ->data($opportunity)
            ->autoCheck()
            ->batchCheck($this->config->opportunity->require->edit, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        if(dao::isError()) return false;
        return commonModel::createChanges($old, $opportunity);
    }

    /**
     * Move opportunity to a new stage.
     */
    public function move($id, $toStage)
    {
        $opp = $this->getByID($id);
        if(!$opp) return false;

        $this->dao->update(TABLE_OPPORTUNITY)
            ->set('stage')->eq($toStage)
            ->set('probability')->eq($this->getStageProbability($toStage))
            ->set('editedBy')->eq($this->app->user->account)
            ->set('editedDate')->eq(helper::now())
            ->where('id')->eq($id)
            ->exec();

        $this->logStage($id, $opp->stage, $toStage);
        return !dao::isError();
    }

    /**
     * Win opportunity (convert to order).
     */
    public function win($id)
    {
        $this->dao->update(TABLE_OPPORTUNITY)
            ->set('status')->eq('won')
            ->set('stage')->eq('won')
            ->set('probability')->eq(100)
            ->set('closedDate')->eq(helper::now())
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * Lose opportunity.
     */
    public function lose($id, $reason = '')
    {
        $this->dao->update(TABLE_OPPORTUNITY)
            ->set('status')->eq('lost')
            ->set('stage')->eq('lost')
            ->set('probability')->eq(0)
            ->set('lostReason')->eq($reason)
            ->set('closedDate')->eq(helper::now())
            ->where('id')->eq($id)
            ->exec();
        return !dao::isError();
    }

    /**
     * Get funnel data (count and amount per stage).
     */
    public function getFunnel()
    {
        return $this->dao->select('stage, COUNT(*) as cnt, SUM(amount) as totalAmount')
            ->from(TABLE_OPPORTUNITY)
            ->where('status')->eq('open')
            ->groupBy('stage')
            ->fetchAll('stage');
    }

    /**
     * Get pipeline data (grouped by stage for kanban view).
     */
    public function getPipeline($owner = '')
    {
        return $this->dao->select('*')->from(TABLE_OPPORTUNITY)
            ->where('status')->eq('open')
            ->beginIF($owner)->andWhere('owner')->eq($owner)->fi()
            ->orderBy('stage_asc, amount_desc')
            ->fetchGroup('stage', 'id');
    }

    private function logStage($id, $from, $to)
    {
        $log = new stdclass();
        $log->opportunity  = $id;
        $log->fromStage    = $from;
        $log->toStage      = $to;
        $log->operator     = $this->app->user->account;
        $log->operatedDate = helper::now();
        $this->dao->insert(TABLE_OPPORTUNITYLOG)->data($log)->exec();
    }

    private function getStageProbability($stage)
    {
        $map = array('initial' => 10, 'demand' => 30, 'proposal' => 50, 'negotiate' => 70, 'won' => 100, 'lost' => 0);
        return isset($map[$stage]) ? $map[$stage] : 0;
    }
}
