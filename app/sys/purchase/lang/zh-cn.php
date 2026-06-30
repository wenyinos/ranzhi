<?php
if(!isset($lang->purchase)) $lang->purchase = new stdclass();
$lang->purchase->common      = '采购管理';
$lang->purchase->id          = '编号';
$lang->purchase->code        = '采购单号';
$lang->purchase->provider    = '供应商';
$lang->purchase->status      = '状态';
$lang->purchase->totalAmount = '总金额';
$lang->purchase->currency    = '币种';
$lang->purchase->purchaser   = '采购员';
$lang->purchase->orderDate   = '下单日期';
$lang->purchase->receiveDate = '预计到货';
$lang->purchase->reviewedBy  = '审核人';
$lang->purchase->reviewedDate = '审核时间';
$lang->purchase->description = '备注';
$lang->purchase->createdBy   = '创建者';
$lang->purchase->createdDate = '创建时间';
$lang->purchase->editedBy    = '编辑者';
$lang->purchase->editedDate  = '编辑时间';

$lang->purchase->index   = '浏览采购单';
$lang->purchase->browse  = '采购单列表';
$lang->purchase->create  = '新建采购单';
$lang->purchase->edit    = '编辑采购单';
$lang->purchase->view    = '采购单详情';
$lang->purchase->submit  = '提交审批';
$lang->purchase->approve = '审批通过';
$lang->purchase->reject  = '审批驳回';
$lang->purchase->receive = '入库';
$lang->purchase->delete  = '删除';

$lang->purchase->productName = '产品名称';
$lang->purchase->spec        = '规格型号';
$lang->purchase->unit        = '单位';
$lang->purchase->quantity    = '数量';
$lang->purchase->price       = '单价';
$lang->purchase->amount      = '金额';
$lang->purchase->receivedQty = '已入库';
$lang->purchase->remainQty   = '待入库';

$lang->purchase->submitSuccess  = '已提交审批';
$lang->purchase->approveSuccess = '审批通过';
$lang->purchase->rejectSuccess  = '已驳回';
$lang->purchase->receiveSuccess = '入库成功';
$lang->purchase->errorDeleteNotDraft = '只有草稿状态的采购单才能删除';

$lang->purchase->statusList['draft']  = '草稿';
$lang->purchase->statusList['wait']   = '待审批';
$lang->purchase->statusList['pass']   = '已审批';
$lang->purchase->statusList['reject'] = '已驳回';
$lang->purchase->statusList['closed'] = '已关闭';
