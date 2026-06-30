<?php
if(!isset($lang->invoice)) $lang->invoice = new stdclass();
$lang->invoice->common      = 'Invoice';
$lang->invoice->browse      = 'Invoices';
$lang->invoice->create      = 'Create Invoice';
$lang->invoice->edit        = 'Edit Invoice';
$lang->invoice->view        = 'Invoice Detail';
$lang->invoice->verify      = 'Verify';
$lang->invoice->delete      = 'Delete';
$lang->invoice->verifySuccess = 'Invoice verified';

$lang->invoice->typeList['input']  = 'Input';
$lang->invoice->typeList['output'] = 'Output';

$lang->invoice->statusList['draft']   = 'Draft';
$lang->invoice->statusList['issued']  = 'Issued';
$lang->invoice->statusList['verified'] = 'Verified';
