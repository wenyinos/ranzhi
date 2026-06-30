<?php
$lang->app = new stdclass();
$lang->app->name = 'Inventory';

$lang->menu->inventory = new stdclass();
$lang->menu->inventory->purchase  = 'Purchase|purchase|browse|';
$lang->menu->inventory->warehouse = 'Warehouse|warehouse|browse|';
$lang->menu->inventory->stock     = 'Stock|stock|browse|';

if(!isset($lang->purchase)) $lang->purchase = new stdclass();
$lang->purchase->menu = new stdclass();
$lang->purchase->menu->browse = 'All Purchases|purchase|browse|status=all';
$lang->purchase->menu->wait   = 'Pending|purchase|browse|status=wait';
$lang->purchase->menu->pass   = 'Approved|purchase|browse|status=pass';

if(!isset($lang->warehouse)) $lang->warehouse = new stdclass();
$lang->warehouse->menu = new stdclass();
$lang->warehouse->menu->browse = array('link' => 'Warehouses|warehouse|browse|', 'alias' => 'create,edit,view');

if(!isset($lang->stock)) $lang->stock = new stdclass();
$lang->stock->menu = new stdclass();
$lang->stock->menu->browse   = 'Stock Overview|stock|browse|';
$lang->stock->menu->log      = 'Stock Log|stock|log|';
$lang->stock->menu->inbound  = 'Inbound|stock|inbound|';
$lang->stock->menu->outbound = 'Outbound|stock|outbound|';

$lang->inventory->menuOrder = array();
$lang->inventory->menuOrder[5]  = 'purchase';
$lang->inventory->menuOrder[10] = 'warehouse';
$lang->inventory->menuOrder[15] = 'stock';
