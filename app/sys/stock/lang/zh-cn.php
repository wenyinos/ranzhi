<?php
if(!isset($lang->stock)) $lang->stock = new stdclass();
$lang->stock->common      = '库存管理';
$lang->stock->browse      = '库存总览';
$lang->stock->log         = '出入库流水';
$lang->stock->inbound     = '入库';
$lang->stock->outbound    = '出库';
$lang->stock->alert       = '库存预警';
$lang->stock->productName = '产品名称';
$lang->stock->productCode = '产品编码';
$lang->stock->warehouse   = '仓库';
$lang->stock->quantity    = '库存数量';
$lang->stock->costPrice   = '成本价';
$lang->stock->safetyStock = '安全库存';
$lang->stock->type        = '类型';
$lang->stock->beforeQty   = '变动前';
$lang->stock->afterQty    = '变动后';
$lang->stock->operator    = '操作人';
$lang->stock->operatedDate = '操作时间';
$lang->stock->description = '说明';
$lang->stock->sourceType  = '来源';
$lang->stock->product     = '产品';

$lang->stock->inboundSuccess  = '入库成功';
$lang->stock->outboundSuccess = '出库成功';
$lang->stock->errorInsufficient = '库存不足';

$lang->stock->typeList['in']     = '入库';
$lang->stock->typeList['out']    = '出库';
$lang->stock->typeList['adjust'] = '调整';

$lang->stock->sourceTypeList['purchase'] = '采购入库';
$lang->stock->sourceTypeList['sales']    = '销售出库';
$lang->stock->sourceTypeList['manual']   = '手动操作';
