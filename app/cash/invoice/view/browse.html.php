<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php commonModel::printLink('invoice', 'create', '', '<i class="icon-plus"></i> ' . $lang->invoice->create, "class='btn btn-primary' data-toggle='modal' data-width='700'");?>
</div>
<div class='main panel'>
  <table class='table table-bordered table-hover table-striped tablesorter table-data'>
    <thead>
      <tr class='text-center'>
        <?php $vars = "type={$type}&status={$status}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <th class='w-60px'> <?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->invoice->id);?></th>
        <th class='w-120px'><?php commonModel::printOrderLink('code', $orderBy, $vars, $lang->invoice->code);?></th>
        <th class='w-60px'> <?php echo $lang->invoice->type;?></th>
        <th class='w-80px'> <?php echo $lang->invoice->status;?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('amount', $orderBy, $vars, $lang->invoice->amount);?></th>
        <th class='w-80px'> <?php echo $lang->invoice->taxAmount;?></th>
        <th class='w-100px'><?php echo $lang->invoice->totalAmount;?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('issueDate', $orderBy, $vars, $lang->invoice->issueDate);?></th>
        <th class='w-100px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($invoices as $invoice):?>
      <tr class='text-center' data-url="<?php echo $this->createLink('invoice', 'view', "invoiceID={$invoice->id}");?>">
        <td><?php echo $invoice->id;?></td>
        <td><?php echo $invoice->code;?></td>
        <td><?php echo $lang->invoice->typeList[$invoice->type];?></td>
        <td><span class='label label-<?php echo $invoice->status == "verified" ? "success" : ($invoice->status == "issued" ? "info" : "default");?>'><?php echo $lang->invoice->statusList[$invoice->status];?></span></td>
        <td class='text-right'><?php echo $invoice->amount;?></td>
        <td class='text-right'><?php echo $invoice->taxAmount;?></td>
        <td class='text-right'><?php echo $invoice->totalAmount;?></td>
        <td><?php echo $invoice->issueDate;?></td>
        <td>
          <?php
          commonModel::printLink('invoice', 'edit', "invoiceID=$invoice->id", $lang->edit, "data-toggle='modal' data-width='700'");
          if($invoice->status == 'issued') commonModel::printLink('invoice', 'verify', "invoiceID=$invoice->id", $lang->invoice->verify, "class='confirm'");
          commonModel::printLink('invoice', 'delete', "invoiceID=$invoice->id", $lang->delete, "class='reloadDeleter'");
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show();?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
