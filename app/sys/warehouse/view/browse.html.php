<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('status', $status);?>
<div id='menuActions'>
  <?php commonModel::printLink('warehouse', 'create', '', '<i class="icon-plus"></i> ' . $lang->warehouse->create, "class='btn btn-primary' data-toggle='modal' data-width='600'");?>
</div>
<div class='main panel'>
  <table class='table table-bordered table-hover table-striped tablesorter table-data' id='warehouseList'>
    <thead>
      <tr class='text-center'>
        <?php $vars = "status={$status}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <th class='w-60px'> <?php commonModel::printOrderLink('id',          $orderBy, $vars, $lang->warehouse->id);?></th>
        <th>                <?php commonModel::printOrderLink('name',        $orderBy, $vars, $lang->warehouse->name);?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('code',        $orderBy, $vars, $lang->warehouse->code);?></th>
        <th class='w-200px'><?php commonModel::printOrderLink('address',     $orderBy, $vars, $lang->warehouse->address);?></th>
        <th class='w-80px'> <?php echo $lang->warehouse->manager;?></th>
        <th class='w-80px'> <?php commonModel::printOrderLink('status',      $orderBy, $vars, $lang->warehouse->status);?></th>
        <th class='w-100px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($warehouses as $warehouse):?>
      <tr class='text-center' data-url="<?php echo $this->createLink('warehouse', 'view', "warehouseID={$warehouse->id}");?>">
        <td><?php echo $warehouse->id;?></td>
        <td class='text-left'><?php echo $warehouse->name;?></td>
        <td><?php echo $warehouse->code;?></td>
        <td class='text-left'><?php echo $warehouse->address;?></td>
        <td><?php echo $warehouse->manager;?></td>
        <td><?php echo $lang->warehouse->statusList[$warehouse->status];?></td>
        <td>
          <?php
          commonModel::printLink('warehouse', 'edit', "warehouseID=$warehouse->id", $lang->edit, "data-toggle='modal' data-width='600'");
          commonModel::printLink('warehouse', 'delete', "warehouseID=$warehouse->id", $lang->delete, "class='reloadDeleter'");
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show();?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
