<?php
$lang->app = new stdclass();
$lang->app->name = '进销存';

/* Top-level sidebar menu. */
$lang->menu->inventory = new stdclass();
$lang->menu->inventory->purchase  = '采购|purchase|browse|';
$lang->menu->inventory->warehouse = '仓库|warehouse|browse|';
$lang->menu->inventory->stock     = '库存|stock|browse|';

/* Sub-menus for purchase. */
if(!isset($lang->purchase)) $lang->purchase = new stdclass();
$lang->purchase->menu = new stdclass();
$lang->purchase->menu->browse = '所有采购单|purchase|browse|status=all';
$lang->purchase->menu->wait   = '待审批|purchase|browse|status=wait';
$lang->purchase->menu->pass   = '已审批|purchase|browse|status=pass';

/* Sub-menus for warehouse. */
if(!isset($lang->warehouse)) $lang->warehouse = new stdclass();
$lang->warehouse->menu = new stdclass();
$lang->warehouse->menu->browse = array('link' => '仓库列表|warehouse|browse|', 'alias' => 'create,edit,view');

/* Sub-menus for stock. */
if(!isset($lang->stock)) $lang->stock = new stdclass();
$lang->stock->menu = new stdclass();
$lang->stock->menu->browse   = '库存总览|stock|browse|';
$lang->stock->menu->log      = '出入库流水|stock|log|';
$lang->stock->menu->inbound  = '入库|stock|inbound|';
$lang->stock->menu->outbound = '出库|stock|outbound|';

/* Menu order. */
$lang->inventory->menuOrder = array();
$lang->inventory->menuOrder[5]  = 'purchase';
$lang->inventory->menuOrder[10] = 'warehouse';
$lang->inventory->menuOrder[15] = 'stock';
