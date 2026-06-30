<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php commonModel::printLink('stock', 'browse', '', '<i class="icon-arrow-left"></i> ' . $lang->stock->browse, "class='btn btn-default'");?>
</div>
<div class='main panel'>
  <table class='table table-bordered table-hover table-striped tablesorter table-data'>
    <thead>
      <tr class='text-center'>
        <th class='w-60px'><?php echo $lang->stock->type;?></th>
        <th><?php echo $lang->stock->warehouse;?></th>
        <th><?php echo $lang->stock->productName;?></th>
        <th class='w-80px'><?php echo $lang->stock->quantity;?></th>
        <th class='w-80px'><?php echo $lang->stock->beforeQty;?></th>
        <th class='w-80px'><?php echo $lang->stock->afterQty;?></th>
        <th class='w-80px'><?php echo $lang->stock->sourceType;?></th>
        <th class='w-80px'><?php echo $lang->stock->operator;?></th>
        <th class='w-160px'><?php echo $lang->stock->operatedDate;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($logs as $log):?>
      <tr class='text-center'>
        <td><span class='label label-<?php echo $log->type == "in" ? "success" : ($log->type == "out" ? "danger" : "info");?>'><?php echo $lang->stock->typeList[$log->type];?></span></td>
        <td><?php echo $log->warehouseName;?></td>
        <td class='text-left'><?php echo $log->productName;?></td>
        <td class='text-right'><?php echo $log->quantity;?></td>
        <td class='text-right'><?php echo $log->beforeQty;?></td>
        <td class='text-right'><?php echo $log->afterQty;?></td>
        <td><?php echo isset($lang->stock->sourceTypeList[$log->sourceType]) ? $lang->stock->sourceTypeList[$log->sourceType] : $log->sourceType;?></td>
        <td><?php echo $log->operator;?></td>
        <td><?php echo $log->operatedDate;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show();?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
