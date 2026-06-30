<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->warehouse->view;?></strong>
    <div class='pull-right'>
      <?php commonModel::printLink('warehouse', 'edit', "warehouseID={$warehouse->id}", $lang->edit, "data-toggle='modal' data-width='600'");?>
    </div>
  </div>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->warehouse->name;?></th>
      <td><?php echo $warehouse->name;?></td>
      <th class='w-80px'><?php echo $lang->warehouse->code;?></th>
      <td><?php echo $warehouse->code;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->address;?></th>
      <td colspan='3'><?php echo $warehouse->address;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->manager;?></th>
      <td><?php echo $warehouse->manager;?></td>
      <th><?php echo $lang->warehouse->status;?></th>
      <td><?php echo $lang->warehouse->statusList[$warehouse->status];?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->desc;?></th>
      <td colspan='3'><?php echo $warehouse->description;?></td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
