<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->invoice->view . ' #' . $invoice->code;?></strong>
    <span class='label label-<?php echo $invoice->status == "verified" ? "success" : "default";?>' style='margin-left:10px;'><?php echo $lang->invoice->statusList[$invoice->status];?></span>
    <div class='pull-right'>
      <?php commonModel::printLink('invoice', 'edit', "invoiceID={$invoice->id}", $lang->edit, "data-toggle='modal' data-width='700'");?>
    </div>
  </div>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->invoice->code;?></th>
      <td><?php echo $invoice->code;?></td>
      <th class='w-80px'><?php echo $lang->invoice->type;?></th>
      <td><?php echo $lang->invoice->typeList[$invoice->type];?></td>
    </tr>
    <tr>
      <th><?php echo $lang->invoice->customer;?></th>
      <td><?php echo $invoice->customer;?></td>
      <th><?php echo $lang->invoice->issueDate;?></th>
      <td><?php echo $invoice->issueDate;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->invoice->amount;?></th>
      <td><?php echo $invoice->amount;?></td>
      <th><?php echo $lang->invoice->taxRate;?></th>
      <td><?php echo $invoice->taxRate;?>%</td>
    </tr>
    <tr>
      <th><?php echo $lang->invoice->taxAmount;?></th>
      <td><?php echo $invoice->taxAmount;?></td>
      <th><?php echo $lang->invoice->totalAmount;?></th>
      <td><strong><?php echo $invoice->totalAmount;?></strong></td>
    </tr>
    <tr>
      <th><?php echo $lang->invoice->description;?></th>
      <td colspan='3'><?php echo $invoice->description;?></td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
