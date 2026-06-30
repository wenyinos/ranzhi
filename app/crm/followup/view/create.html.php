<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='followupForm' class='form-ajax'>
  <input type='hidden' name='objectType' value='<?php echo $this->get->objectType;?>'/>
  <input type='hidden' name='objectId' value='<?php echo $this->get->objectId;?>'/>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->followup->type;?></th>
      <td><?php echo html::select('type', $lang->followup->typeList, 'phone', "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->followup->content;?></th>
      <td><?php echo html::textarea('content', '', "class='form-control' rows='4'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->followup->nextDate;?></th>
      <td><?php echo html::input('nextDate', '', "class='form-control form-date'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->followup->nextPlan;?></th>
      <td><?php echo html::input('nextPlan', '', "class='form-control'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
