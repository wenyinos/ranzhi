<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->quote->view . ' #' . $quote->code;?></strong>
    <span class='label label-<?php echo $quote->status == "accepted" ? "success" : ($quote->status == "rejected" ? "danger" : "default");?>' style='margin-left:10px;'><?php echo $lang->quote->statusList[$quote->status];?></span>
  </div>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->quote->customer;?></th>
      <td><?php echo $quote->customer;?></td>
      <th class='w-80px'><?php echo $lang->quote->validUntil;?></th>
      <td><?php echo $quote->validUntil;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->quote->terms;?></th>
      <td colspan='3'><?php echo $quote->terms;?></td>
    </tr>
  </table>
  <table class='table table-bordered table-hover'>
    <thead>
      <tr>
        <th class='w-60px'>#</th>
        <th><?php echo $lang->quote->productName;?></th>
        <th class='w-80px'><?php echo $lang->quote->quantity;?></th>
        <th class='w-100px'><?php echo $lang->quote->price;?></th>
        <th class='w-100px'><?php echo $lang->quote->amount;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 0; foreach($quote->items as $item): $i++;?>
      <tr>
        <td><?php echo $i;?></td>
        <td><?php echo $item->productName;?></td>
        <td class='text-right'><?php echo $item->quantity;?></td>
        <td class='text-right'><?php echo $item->price;?></td>
        <td class='text-right'><?php echo $item->amount;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan='4' class='text-right'><?php echo $lang->quote->totalAmount;?></th>
        <th class='text-right'><?php echo $quote->totalAmount;?></th>
      </tr>
    </tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
