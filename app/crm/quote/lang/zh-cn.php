<?php
if(!isset($lang->quote)) $lang->quote = new stdclass();
$lang->quote->common      = '报价管理';
$lang->quote->id          = '编号';
$lang->quote->code        = '报价单号';
$lang->quote->customer    = '客户';
$lang->quote->contact     = '联系人';
$lang->quote->opportunity = '关联商机';
$lang->quote->status      = '状态';
$lang->quote->totalAmount = '总金额';
$lang->quote->discount    = '折扣(%)';
$lang->quote->finalAmount = '折后金额';
$lang->quote->currency    = '币种';
$lang->quote->validUntil  = '有效期';
$lang->quote->terms       = '条款';
$lang->quote->owner       = '负责人';
$lang->quote->createdBy   = '创建者';
$lang->quote->createdDate = '创建时间';

$lang->quote->index  = '浏览报价单';
$lang->quote->browse = '报价单列表';
$lang->quote->create = '新建报价单';
$lang->quote->edit   = '编辑报价单';
$lang->quote->view   = '报价单详情';
$lang->quote->send   = '发送';
$lang->quote->accept = '接受';
$lang->quote->reject = '拒绝';
$lang->quote->delete = '删除';

$lang->quote->productName = '产品';
$lang->quote->quantity    = '数量';
$lang->quote->price       = '单价';
$lang->quote->amount      = '金额';

$lang->quote->statusList['draft']   = '草稿';
$lang->quote->statusList['sent']    = '已发送';
$lang->quote->statusList['accepted']= '已接受';
$lang->quote->statusList['rejected']= '已拒绝';
