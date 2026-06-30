<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='purchaseForm' class='form-ajax'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->purchase->code;?></th>
      <td><?php echo html::input('code', '', "class='form-control'");?></td>
      <th class='w-80px'><?php echo $lang->purchase->provider;?></th>
      <td><?php echo html::select('provider', $providers, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->purchase->orderDate;?></th>
      <td><?php echo html::input('orderDate', helper::today(), "class='form-control form-date'");?></td>
      <th><?php echo $lang->purchase->receiveDate;?></th>
      <td><?php echo html::input('receiveDate', '', "class='form-control form-date'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->purchase->description;?></th>
      <td colspan='3'><?php echo html::textarea('description', '', "class='form-control' rows='2'");?></td>
    </tr>
  </table>

  <div class='table-footer'><strong><?php echo $lang->purchase->productName;?></strong></div>
  <table class='table table-bordered table-detail' id='detailTable'>
    <thead>
      <tr>
        <th class='w-200px'><?php echo $lang->purchase->productName;?></th>
        <th class='w-120px'><?php echo $lang->purchase->spec;?></th>
        <th class='w-60px'><?php echo $lang->purchase->unit;?></th>
        <th class='w-80px'><?php echo $lang->purchase->quantity;?></th>
        <th class='w-100px'><?php echo $lang->purchase->price;?></th>
        <th class='w-100px'><?php echo $lang->purchase->amount;?></th>
        <th class='w-60px'></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo html::select('productList[1]', $products, '', "class='form-control chosen product-select'");?></td>
        <td><?php echo html::input('specList[1]', '', "class='form-control'");?></td>
        <td><?php echo html::input('unitList[1]', '', "class='form-control'");?></td>
        <td><?php echo html::input('quantityList[1]', '', "class='form-control text-right qty-input'");?></td>
        <td><?php echo html::input('priceList[1]', '', "class='form-control text-right price-input'");?></td>
        <td class='text-right amount-cell'>0.00</td>
        <td><i class='btn btn-mini icon-plus add-row'></i></td>
      </tr>
    </tbody>
  </table>
  <div class='text-center' style='padding:10px;'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></div>
</form>
<script type='text/template' id='detailTpl'>
<tr>
  <td><?php echo html::select('productList[key]', $products, '', "class='form-control chosen product-select'");?></td>
  <td><?php echo html::input('specList[key]', '', "class='form-control'");?></td>
  <td><?php echo html::input('unitList[key]', '', "class='form-control'");?></td>
  <td><?php echo html::input('quantityList[key]', '', "class='form-control text-right qty-input'");?></td>
  <td><?php echo html::input('priceList[key]', '', "class='form-control text-right price-input'");?></td>
  <td class='text-right amount-cell'>0.00</td>
  <td><i class='btn btn-mini icon-remove remove-row'></i></td>
</tr>
</script>
<script>
$(document).on('click', '.add-row', function(){
    var tpl = $('#detailTpl').html();
    var key = $('#detailTable tbody tr').length + 1;
    tpl = tpl.replace(/\[key\]/g, '[' + key + ']');
    $('#detailTable tbody').append(tpl);
});
$(document).on('click', '.remove-row', function(){
    $(this).closest('tr').remove();
});
$(document).on('input', '.qty-input, .price-input', function(){
    var tr = $(this).closest('tr');
    var qty = parseFloat(tr.find('.qty-input').val()) || 0;
    var price = parseFloat(tr.find('.price-input').val()) || 0;
    tr.find('.amount-cell').text((qty * price).toFixed(2));
});
</script>
<?php include '../../common/view/footer.modal.html.php';?>
