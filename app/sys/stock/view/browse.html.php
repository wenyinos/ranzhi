<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php commonModel::printLink('stock', 'inbound', '', '<i class="icon-plus"></i> ' . $lang->stock->inbound, "class='btn btn-primary' data-toggle='modal' data-width='600'");?>
  <?php commonModel::printLink('stock', 'outbound', '', '<i class="icon-minus"></i> ' . $lang->stock->outbound, "class='btn btn-default' data-toggle='modal' data-width='600'");?>
  <?php commonModel::printLink('stock', 'log', '', '<i class="icon-list"></i> ' . $lang->stock->log, "class='btn btn-default'");?>
</div>
<div class='main panel'>
  <table class='table table-bordered table-hover table-striped tablesorter table-data'>
    <thead>
      <tr class='text-center'>
        <th><?php echo $lang->stock->warehouse;?></th>
        <th><?php echo $lang->stock->productName;?></th>
        <th class='w-120px'><?php echo $lang->stock->quantity;?></th>
        <th class='w-120px'><?php echo $lang->stock->safetyStock;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($stocks as $stock):?>
      <tr class='text-center'>
        <td><?php echo $stock->warehouseName;?></td>
        <td class='text-left'><?php echo $stock->productName;?></td>
        <td class='text-right'><?php echo $stock->quantity;?></td>
        <td class='text-right'><?php echo $stock->safetyStock;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show();?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
