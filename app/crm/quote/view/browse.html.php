<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php commonModel::printLink('quote', 'create', '', '<i class="icon-plus"></i> ' . $lang->quote->create, "class='btn btn-primary' data-toggle='modal' data-width='900'");?>
</div>
<div class='main panel'>
  <table class='table table-bordered table-hover table-striped tablesorter table-data'>
    <thead>
      <tr class='text-center'>
        <th class='w-60px'><?php echo $lang->quote->id;?></th>
        <th class='w-120px'><?php echo $lang->quote->code;?></th>
        <th><?php echo $lang->quote->customer;?></th>
        <th class='w-80px'><?php echo $lang->quote->status;?></th>
        <th class='w-100px'><?php echo $lang->quote->totalAmount;?></th>
        <th class='w-100px'><?php echo $lang->quote->validUntil;?></th>
        <th class='w-100px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($quotes as $quote):?>
      <tr class='text-center' data-url="<?php echo $this->createLink('quote', 'view', "quoteID={$quote->id}");?>">
        <td><?php echo $quote->id;?></td>
        <td><?php echo $quote->code;?></td>
        <td class='text-left'><?php echo $quote->customer;?></td>
        <td><span class='label label-<?php echo $quote->status == "accepted" ? "success" : ($quote->status == "rejected" ? "danger" : "default");?>'><?php echo $lang->quote->statusList[$quote->status];?></span></td>
        <td class='text-right'><?php echo $quote->finalAmount;?></td>
        <td><?php echo $quote->validUntil;?></td>
        <td>
          <?php
          if($quote->status == 'draft')
          {
              commonModel::printLink('quote', 'edit', "quoteID=$quote->id", $lang->edit, "data-toggle='modal' data-width='900'");
              commonModel::printLink('quote', 'sendQuote', "quoteID=$quote->id", $lang->quote->send, "class='confirm'");
              commonModel::printLink('quote', 'delete', "quoteID=$quote->id", $lang->delete, "class='reloadDeleter'");
          }
          elseif($quote->status == 'sent')
          {
              commonModel::printLink('quote', 'accept', "quoteID=$quote->id", $lang->quote->accept, "class='confirm'");
              commonModel::printLink('quote', 'reject', "quoteID=$quote->id", $lang->quote->reject, "class='confirm'");
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
