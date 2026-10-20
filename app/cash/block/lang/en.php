<?php
/**
 * The en file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     block 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->block->common    = 'Blocks';
$lang->block->depositor = 'Checking Account';
$lang->block->lblBlock  = 'Block';
$lang->block->admin     = 'Manage Block';
$lang->block->num       = 'Number';
$lang->block->orderBy   = 'Order';

$lang->block->availableBlocks = new stdclass();
$lang->block->availableBlocks->depositor = 'Checking Account';
$lang->block->availableBlocks->trade     = 'Trade';
$lang->block->availableBlocks->provider  = 'Suppliers';
$lang->block->availableBlocks->report    = 'Report';

$lang->block->orderByList = new stdclass();
$lang->block->orderByList->trade = array();
$lang->block->orderByList->trade['id_asc']  = 'ID ASC';
$lang->block->orderByList->trade['id_desc'] = 'ID DESC';

$lang->block->typeList = new stdclass();
$lang->block->typeList->trade = array();
$lang->block->typeList->trade['all']  = 'All';
$lang->block->typeList->trade['in']   = 'Income';
$lang->block->typeList->trade['out']  = 'Expense';

$lang->block->groupByList = new stdclass();
$lang->block->groupByList->trade = array();
$lang->block->groupByList->trade['category'] = 'by category';
$lang->block->groupByList->trade['dept']     = 'by deptment';

$lang->block->orderByList->provider = array();
$lang->block->orderByList->provider['id_asc']  = 'ID ASC';
$lang->block->orderByList->provider['id_desc'] = 'ID DESC';
