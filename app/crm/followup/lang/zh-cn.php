<?php
if(!isset($lang->followup)) $lang->followup = new stdclass();
$lang->followup->common       = '跟进记录';
$lang->followup->id           = '编号';
$lang->followup->objectType   = '关联类型';
$lang->followup->objectId     = '关联ID';
$lang->followup->type         = '跟进方式';
$lang->followup->content      = '跟进内容';
$lang->followup->nextDate     = '下次跟进';
$lang->followup->nextPlan     = '跟进计划';
$lang->followup->operator     = '跟进人';
$lang->followup->operatedDate = '跟进时间';

$lang->followup->index    = '跟进记录';
$lang->followup->create   = '新建跟进';
$lang->followup->edit     = '编辑跟进';
$lang->followup->timeline = '跟进时间线';
$lang->followup->delete   = '删除';

$lang->followup->typeList['phone']   = '电话';
$lang->followup->typeList['email']   = '邮件';
$lang->followup->typeList['visit']   = '拜访';
$lang->followup->typeList['wechat']  = '微信';
$lang->followup->typeList['meeting'] = '会议';
$lang->followup->typeList['other']   = '其他';

$lang->followup->objectTypeList['customer']    = '客户';
$lang->followup->objectTypeList['opportunity'] = '商机';
$lang->followup->objectTypeList['contract']    = '合同';
$lang->followup->objectTypeList['order']       = '订单';
