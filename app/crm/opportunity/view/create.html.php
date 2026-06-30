<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='opportunityForm' class='form-ajax'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->opportunity->name;?></th>
      <td><?php echo html::input('name', '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->opportunity->customer;?></th>
      <td><?php echo html::select('customer', $customers, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->opportunity->amount;?></th>
      <td><?php echo html::input('amount', '', "class='form-control text-right'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->opportunity->source;?></th>
      <td><?php echo html::select('source', $lang->opportunity->sourceList, '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->opportunity->expectedDate;?></th>
      <td><?php echo html::input('expectedDate', '', "class='form-control form-date'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->opportunity->description;?></th>
      <td><?php echo html::textarea('description', '', "class='form-control' rows='3'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
