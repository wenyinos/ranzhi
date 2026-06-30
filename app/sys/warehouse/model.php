<?php
class warehouseModel extends model
{
    public function getByID($id = 0)
    {
        return $this->dao->select('*')->from(TABLE_WAREHOUSE)->where('id')->eq($id)->fetch();
    }

    public function getList($status = '', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_WAREHOUSE)
            ->where('deleted')->eq(0)
            ->beginIF($status && $status != 'all')->andWhere('status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    public function getPairs($status = 'active', $orderBy = 'id_desc')
    {
        return $this->dao->select('id, name')->from(TABLE_WAREHOUSE)
            ->where('deleted')->eq(0)
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->fetchPairs('id');
    }

    public function create()
    {
        $warehouse = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->insert(TABLE_WAREHOUSE)
            ->data($warehouse)
            ->autoCheck()
            ->batchCheck($this->config->warehouse->require->create, 'notempty')
            ->check('code', 'unique')
            ->exec();

        return $this->dao->lastInsertID();
    }

    public function update($warehouseID = 0)
    {
        $oldWarehouse = $this->getByID($warehouseID);

        $warehouse = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $this->dao->update(TABLE_WAREHOUSE)
            ->data($warehouse)
            ->autoCheck()
            ->batchCheck($this->config->warehouse->require->edit, 'notempty')
            ->check('code', 'unique', "id!={$warehouseID}")
            ->where('id')->eq($warehouseID)
            ->exec();

        if(dao::isError()) return false;
        return commonModel::createChanges($oldWarehouse, $warehouse);
    }
}
