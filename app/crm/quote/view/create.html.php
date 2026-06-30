<?php
/**
 * The view file for create function of quote module of RanZhi.
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<div class='panel'>
  <div class='panel-heading'><strong><?php echo $lang->quote->create;?></strong></div>
  <div class='panel-body'>
    <form method='post' id='quoteForm' class='form-ajax'>
      <table class='table table-form w-p80'>
        <tr>
          <th class='w-80px'><?php echo $lang->quote->code;?></th>
          <td><?php echo html::input('code', '', "class='form-control'");?></td>
          <th class='w-80px'><?php echo $lang->quote->customer;?></th>
          <td><?php echo html::select('customer', $customers, '', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->quote->validUntil;?></th>
          <td><?php echo html::input('validUntil', '', "class='form-control form-date'");?></td>
          <th><?php echo $lang->quote->discount;?></th>
          <td><?php echo html::input('discount', '0', "class='form-control text-right'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->quote->terms;?></th>
          <td colspan='3'><?php echo html::textarea('terms', '', "class='form-control' rows='2'");?></td>
        </tr>
      </table>

      <h4 style='margin:15px 0 10px;'><?php echo $lang->quote->productName;?></h4>
      <table class='table table-bordered table-detail' id='detailTable'>
        <thead>
          <tr>
            <th class='w-250px'><?php echo $lang->quote->productName;?></th>
            <th class='w-80px'><?php echo $lang->quote->quantity;?></th>
            <th class='w-120px'><?php echo $lang->quote->price;?></th>
            <th class='w-120px'><?php echo $lang->quote->amount;?></th>
            <th class='w-60px'></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo html::select('productList[1]', $products, '', "class='form-control chosen'");?></td>
            <td><?php echo html::input('quantityList[1]', '', "class='form-control text-right qty-input'");?></td>
            <td><?php echo html::input('priceList[1]', '', "class='form-control text-right price-input'");?></td>
            <td class='text-right amount-cell'>0.00</td>
            <td><i class='btn btn-mini icon-plus add-row'></i></td>
          </tr>
        </tbody>
      </table>

      <div class='text-center' style='padding:10px;'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></div>
    </form>
  </div>
</div>
<script type='text/template' id='detailTpl'>
<tr>
  <td><?php echo html::select('productList[key]', $products, '', "class='form-control chosen'");?></td>
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
    $('#detailTable tbody tr:last .chosen').chosen();
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
<?php include '../../common/view/footer.html.php';?>
