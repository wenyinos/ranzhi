<?php
if(!isset($lang->warehouse)) $lang->warehouse = new stdclass();
$lang->warehouse->common      = '仓库管理';
$lang->warehouse->id          = '编号';
$lang->warehouse->name        = '仓库名称';
$lang->warehouse->code        = '仓库编码';
$lang->warehouse->address     = '地址';
$lang->warehouse->manager     = '管理员';
$lang->warehouse->status      = '状态';
$lang->warehouse->desc        = '说明';
$lang->warehouse->createdBy   = '创建者';
$lang->warehouse->createdDate = '创建时间';
$lang->warehouse->editedBy    = '编辑者';
$lang->warehouse->editedDate  = '编辑时间';

$lang->warehouse->index  = '浏览仓库';
$lang->warehouse->browse = '仓库列表';
$lang->warehouse->create = '添加仓库';
$lang->warehouse->edit   = '编辑仓库';
$lang->warehouse->view   = '仓库详情';
$lang->warehouse->delete = '删除仓库';

$lang->warehouse->statusList['active']   = '启用';
$lang->warehouse->statusList['disabled'] = '停用';
