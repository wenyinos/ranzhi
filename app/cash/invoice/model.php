<?php
class invoiceModel extends model
{
    public function getByID($id)
    {
        return $this->dao->select('*')->from(TABLE_INVOICE)->where('id')->eq($id)->fetch();
    }

    public function getList($type = '', $status = '', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_INVOICE)
            ->where('deleted')->eq(0)
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    public function create()
    {
        $invoice = fixer::input('post')
            ->add('status', 'draft')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->get();

        $invoice->totalAmount = $invoice->amount + $invoice->taxAmount;

        $this->dao->insert(TABLE_INVOICE)
            ->data($invoice)
            ->autoCheck()
            ->batchCheck($this->config->invoice->require->create, 'notempty')
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    public function update($invoiceID)
    {
        $oldInvoice = $this->getByID($invoiceID);

        $invoice = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('status')
            ->get();

        $invoice->totalAmount = $invoice->amount + $invoice->taxAmount;

        $this->dao->update(TABLE_INVOICE)
            ->data($invoice)
            ->autoCheck()
            ->batchCheck($this->config->invoice->require->edit, 'notempty')
            ->where('id')->eq($invoiceID)
            ->exec();

        if(dao::isError()) return false;
        return commonModel::createChanges($oldInvoice, $invoice);
    }

    public function verify($invoiceID)
    {
        $this->dao->update(TABLE_INVOICE)
            ->set('status')->eq('verified')
            ->set('editedBy')->eq($this->app->user->account)
            ->set('editedDate')->eq(helper::now())
            ->where('id')->eq($invoiceID)
            ->exec();
        return !dao::isError();
    }
}
