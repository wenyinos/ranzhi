<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('status', $status);?>
<div id='menuActions'>
  <?php commonModel::printLink('purchase', 'create', '', '<i class="icon-plus"></i> ' . $lang->purchase->create, "class='btn btn-primary'");?>
</div>
<div class='main panel'>
  <table class='table table-bordered table-hover table-striped tablesorter table-data' id='purchaseList'>
    <thead>
      <tr class='text-center'>
        <?php $vars = "status={$status}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <th class='w-60px'> <?php commonModel::printOrderLink('id',          $orderBy, $vars, $lang->purchase->id);?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('code',        $orderBy, $vars, $lang->purchase->code);?></th>
        <th>                <?php echo $lang->purchase->provider;?></th>
        <th class='w-100px'><?php echo $lang->purchase->totalAmount;?></th>
        <th class='w-80px'> <?php echo $lang->purchase->status;?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('orderDate',   $orderBy, $vars, $lang->purchase->orderDate);?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('receiveDate', $orderBy, $vars, $lang->purchase->receiveDate);?></th>
        <th class='w-100px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($purchases as $purchase):?>
      <tr class='text-center' data-url="<?php echo $this->createLink('purchase', 'view', "purchaseID={$purchase->id}");?>">
        <td><?php echo $purchase->id;?></td>
        <td><?php echo $purchase->code;?></td>
        <td class='text-left'><?php echo $purchase->provider;?></td>
        <td class='text-right'><?php echo $purchase->totalAmount;?></td>
        <td><span class='label label-<?php echo $purchase->status == "pass" ? "success" : ($purchase->status == "reject" ? "danger" : "default");?>'><?php echo $lang->purchase->statusList[$purchase->status];?></span></td>
        <td><?php echo $purchase->orderDate;?></td>
        <td><?php echo $purchase->receiveDate;?></td>
        <td>
          <?php
          if($purchase->status == 'draft')
          {
              commonModel::printLink('purchase', 'edit', "purchaseID=$purchase->id", $lang->edit, "data-toggle='modal' data-width='900'");
              commonModel::printLink('purchase', 'submit', "purchaseID=$purchase->id", $lang->purchase->submit, "class='confirm'");
              commonModel::printLink('purchase', 'delete', "purchaseID=$purchase->id", $lang->delete, "class='reloadDeleter'");
          }
          elseif($purchase->status == 'wait')
          {
              commonModel::printLink('purchase', 'approve', "purchaseID=$purchase->id", $lang->purchase->approve, "class='confirm'");
              commonModel::printLink('purchase', 'reject', "purchaseID=$purchase->id", $lang->purchase->reject, "class='confirm'");
          }
          elseif($purchase->status == 'pass')
          {
              commonModel::printLink('purchase', 'receive', "purchaseID=$purchase->id", $lang->purchase->receive, "data-toggle='modal' data-width='700'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show();?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
