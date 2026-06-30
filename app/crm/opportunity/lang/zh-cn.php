<?php
if(!isset($lang->opportunity)) $lang->opportunity = new stdclass();
$lang->opportunity->common        = '商机管理';
$lang->opportunity->id            = '编号';
$lang->opportunity->name          = '商机名称';
$lang->opportunity->customer      = '客户';
$lang->opportunity->contact       = '联系人';
$lang->opportunity->stage         = '阶段';
$lang->opportunity->amount        = '预估金额';
$lang->opportunity->probability   = '赢单概率';
$lang->opportunity->source        = '来源';
$lang->opportunity->owner         = '负责人';
$lang->opportunity->expectedDate  = '预计成交';
$lang->opportunity->lostReason    = '丢单原因';
$lang->opportunity->description   = '描述';
$lang->opportunity->status        = '状态';
$lang->opportunity->closedDate    = '关闭时间';
$lang->opportunity->createdBy     = '创建者';
$lang->opportunity->createdDate   = '创建时间';

$lang->opportunity->index    = '浏览商机';
$lang->opportunity->browse   = '商机列表';
$lang->opportunity->funnel   = '销售漏斗';
$lang->opportunity->pipeline = '管线视图';
$lang->opportunity->create   = '新建商机';
$lang->opportunity->edit     = '编辑商机';
$lang->opportunity->view     = '商机详情';
$lang->opportunity->move     = '移动阶段';
$lang->opportunity->win      = '赢单';
$lang->opportunity->lose     = '丢单';
$lang->opportunity->delete   = '删除';

$lang->opportunity->winSuccess = '商机已赢单';

$lang->opportunity->stageList['initial']   = '初步接触';
$lang->opportunity->stageList['demand']    = '需求确认';
$lang->opportunity->stageList['proposal']  = '方案报价';
$lang->opportunity->stageList['negotiate'] = '商务谈判';
$lang->opportunity->stageList['won']       = '赢单';
$lang->opportunity->stageList['lost']      = '丢单';

$lang->opportunity->statusList['open'] = '进行中';
$lang->opportunity->statusList['won']  = '赢单';
$lang->opportunity->statusList['lost'] = '丢单';

$lang->opportunity->sourceList['ad']        = '广告';
$lang->opportunity->sourceList['referral']  = '转介绍';
$lang->opportunity->sourceList['phone']     = '电话';
$lang->opportunity->sourceList['exhibition']= '展会';
$lang->opportunity->sourceList['website']   = '网站';
$lang->opportunity->sourceList['other']     = '其他';
