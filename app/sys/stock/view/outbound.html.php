<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='outboundForm' class='form-ajax'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->stock->warehouse;?></th>
      <td><?php echo html::select('warehouse', $warehouses, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->stock->productName;?></th>
      <td><?php echo html::select('product', $products, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->stock->quantity;?></th>
      <td><?php echo html::input('quantity', '', "class='form-control text-right'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->stock->description;?></th>
      <td><?php echo html::textarea('description', '', "class='form-control' rows='2'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
