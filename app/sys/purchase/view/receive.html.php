<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='receiveForm' class='form-ajax'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->purchase->code;?></th>
      <td><?php echo $purchase->code;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->name;?></th>
      <td><?php echo html::select('warehouse', $warehouses, '', "class='form-control chosen'");?></td>
    </tr>
  </table>
  <table class='table table-bordered table-hover'>
    <thead>
      <tr>
        <th><?php echo $lang->purchase->productName;?></th>
        <th class='w-80px'><?php echo $lang->purchase->quantity;?></th>
        <th class='w-80px'><?php echo $lang->purchase->receivedQty;?></th>
        <th class='w-80px'><?php echo $lang->purchase->remainQty;?></th>
        <th class='w-100px'><?php echo $lang->purchase->receive;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($purchase->items as $item):?>
      <?php $remain = $item->quantity - $item->receivedQty; if($remain <= 0) continue;?>
      <tr>
        <td><?php echo $item->productName . ($item->spec ? " ({$item->spec})" : '');?></td>
        <td class='text-right'><?php echo $item->quantity;?></td>
        <td class='text-right'><?php echo $item->receivedQty;?></td>
        <td class='text-right'><?php echo $remain;?></td>
        <td><?php echo html::input("receiveQty[{$item->id}]", $remain, "class='form-control text-right' min='0' max='{$remain}'");?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='text-center' style='padding:10px;'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></div>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
