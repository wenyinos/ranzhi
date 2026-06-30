<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='invoiceForm' class='form-ajax'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->invoice->code;?></th>
      <td><?php echo html::input('code', '', "class='form-control'");?></td>
      <th class='w-80px'><?php echo $lang->invoice->type;?></th>
      <td><?php echo html::select('type', $lang->invoice->typeList, '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->invoice->customer;?></th>
      <td><?php echo html::select('customer', $customers, '', "class='form-control chosen'");?></td>
      <th><?php echo $lang->invoice->issueDate;?></th>
      <td><?php echo html::input('issueDate', helper::today(), "class='form-control form-date'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->invoice->amount;?></th>
      <td><?php echo html::input('amount', '', "class='form-control text-right'");?></td>
      <th><?php echo $lang->invoice->taxRate;?></th>
      <td><?php echo html::input('taxRate', '13', "class='form-control text-right'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->invoice->taxAmount;?></th>
      <td><?php echo html::input('taxAmount', '', "class='form-control text-right'");?></td>
      <th></th>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->invoice->description;?></th>
      <td colspan='3'><?php echo html::textarea('description', '', "class='form-control' rows='2'");?></td>
    </tr>
    <tr>
      <td colspan='4' class='text-center'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
