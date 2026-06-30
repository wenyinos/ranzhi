<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='warehouseForm' class='form-ajax'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->warehouse->name;?></th>
      <td><?php echo html::input('name', '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->code;?></th>
      <td><?php echo html::input('code', '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->address;?></th>
      <td><?php echo html::input('address', '', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->manager;?></th>
      <td><?php echo html::select('manager', $users, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->status;?></th>
      <td><?php echo html::select('status', $lang->warehouse->statusList, 'active', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->warehouse->desc;?></th>
      <td><?php echo html::textarea('description', '', "class='form-control' rows='3'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
