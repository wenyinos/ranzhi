<?php
if(!isset($lang->invoice)) $lang->invoice = new stdclass();
$lang->invoice->common      = '发票管理';
$lang->invoice->id          = '编号';
$lang->invoice->code        = '发票号码';
$lang->invoice->type        = '类型';
$lang->invoice->status      = '状态';
$lang->invoice->amount      = '金额';
$lang->invoice->taxRate     = '税率(%)';
$lang->invoice->taxAmount   = '税额';
$lang->invoice->totalAmount = '价税合计';
$lang->invoice->customer    = '客户/供应商';
$lang->invoice->contract    = '关联合同';
$lang->invoice->issueDate   = '开票日期';
$lang->invoice->description = '备注';
$lang->invoice->createdBy   = '创建者';
$lang->invoice->createdDate = '创建时间';

$lang->invoice->index  = '浏览发票';
$lang->invoice->browse = '发票列表';
$lang->invoice->create = '登记发票';
$lang->invoice->edit   = '编辑发票';
$lang->invoice->view   = '发票详情';
$lang->invoice->verify = '认证';
$lang->invoice->delete = '删除';

$lang->invoice->verifySuccess = '发票已认证';

$lang->invoice->typeList['input']  = '进项';
$lang->invoice->typeList['output'] = '销项';

$lang->invoice->statusList['draft']   = '草稿';
$lang->invoice->statusList['issued']  = '已开';
$lang->invoice->statusList['verified'] = '已认证';
