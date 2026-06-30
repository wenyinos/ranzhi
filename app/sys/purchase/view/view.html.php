<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->purchase->view . ' #' . $purchase->code;?></strong>
    <span class='label label-<?php echo $purchase->status == "pass" ? "success" : ($purchase->status == "reject" ? "danger" : "default");?>' style='margin-left:10px;'><?php echo $lang->purchase->statusList[$purchase->status];?></span>
    <div class='pull-right'>
      <?php
      if($purchase->status == 'draft')
      {
          commonModel::printLink('purchase', 'edit', "purchaseID={$purchase->id}", $lang->edit, "data-toggle='modal' data-width='900'");
          commonModel::printLink('purchase', 'submit', "purchaseID={$purchase->id}", $lang->purchase->submit, "class='confirm'");
      }
      elseif($purchase->status == 'wait')
      {
          commonModel::printLink('purchase', 'approve', "purchaseID={$purchase->id}", $lang->purchase->approve, "class='confirm'");
          commonModel::printLink('purchase', 'reject', "purchaseID={$purchase->id}", $lang->purchase->reject, "class='confirm'");
      }
      elseif($purchase->status == 'pass')
      {
          commonModel::printLink('purchase', 'receive', "purchaseID={$purchase->id}", $lang->purchase->receive, "data-toggle='modal' data-width='700'");
      }
      ?>
    </div>
  </div>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->purchase->code;?></th>
      <td><?php echo $purchase->code;?></td>
      <th class='w-80px'><?php echo $lang->purchase->provider;?></th>
      <td><?php echo $purchase->provider;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->purchase->orderDate;?></th>
      <td><?php echo $purchase->orderDate;?></td>
      <th><?php echo $lang->purchase->receiveDate;?></th>
      <td><?php echo $purchase->receiveDate;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->purchase->reviewedBy;?></th>
      <td><?php echo isset($users[$purchase->reviewedBy]) ? $users[$purchase->reviewedBy] : $purchase->reviewedBy;?></td>
      <th><?php echo $lang->purchase->reviewedDate;?></th>
      <td><?php echo $purchase->reviewedDate;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->purchase->description;?></th>
      <td colspan='3'><?php echo $purchase->description;?></td>
    </tr>
  </table>

  <table class='table table-bordered table-hover'>
    <thead>
      <tr>
        <th class='w-60px'>#</th>
        <th><?php echo $lang->purchase->productName;?></th>
        <th class='w-120px'><?php echo $lang->purchase->spec;?></th>
        <th class='w-60px'><?php echo $lang->purchase->unit;?></th>
        <th class='w-80px'><?php echo $lang->purchase->quantity;?></th>
        <th class='w-100px'><?php echo $lang->purchase->price;?></th>
        <th class='w-100px'><?php echo $lang->purchase->amount;?></th>
        <th class='w-80px'><?php echo $lang->purchase->receivedQty;?></th>
        <th class='w-80px'><?php echo $lang->purchase->remainQty;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 0; foreach($purchase->items as $item): $i++;?>
      <tr>
        <td><?php echo $i;?></td>
        <td><?php echo $item->productName;?></td>
        <td><?php echo $item->spec;?></td>
        <td><?php echo $item->unit;?></td>
        <td class='text-right'><?php echo $item->quantity;?></td>
        <td class='text-right'><?php echo $item->price;?></td>
        <td class='text-right'><?php echo $item->amount;?></td>
        <td class='text-right'><?php echo $item->receivedQty;?></td>
        <td class='text-right'><?php echo $item->quantity - $item->receivedQty;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan='6' class='text-right'><?php echo $lang->purchase->totalAmount;?></th>
        <th class='text-right'><?php echo $purchase->totalAmount;?></th>
        <th colspan='2'></th>
      </tr>
    </tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
