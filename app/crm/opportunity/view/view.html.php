<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->opportunity->view . ': ' . $opportunity->name;?></strong>
    <span class='label label-info' style='margin-left:10px;'><?php echo $lang->opportunity->stageList[$opportunity->stage];?></span>
    <div class='pull-right'>
      <?php commonModel::printLink('opportunity', 'edit', "id={$opportunity->id}", $lang->edit, "data-toggle='modal' data-width='700'");?>
      <?php if($opportunity->status == 'open'):?>
      <?php commonModel::printLink('opportunity', 'win', "id={$opportunity->id}", $lang->opportunity->win, "class='confirm'");?>
      <?php commonModel::printLink('opportunity', 'lose', "id={$opportunity->id}", $lang->opportunity->lose, "class='confirm'");?>
      <?php endif;?>
    </div>
  </div>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->opportunity->customer;?></th>
      <td><?php echo $opportunity->customer;?></td>
      <th class='w-80px'><?php echo $lang->opportunity->owner;?></th>
      <td><?php echo isset($users[$opportunity->owner]) ? $users[$opportunity->owner] : $opportunity->owner;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->opportunity->amount;?></th>
      <td><?php echo $opportunity->amount;?></td>
      <th><?php echo $lang->opportunity->probability;?></th>
      <td><?php echo $opportunity->probability;?>%</td>
    </tr>
    <tr>
      <th><?php echo $lang->opportunity->source;?></th>
      <td><?php echo isset($lang->opportunity->sourceList[$opportunity->source]) ? $lang->opportunity->sourceList[$opportunity->source] : $opportunity->source;?></td>
      <th><?php echo $lang->opportunity->expectedDate;?></th>
      <td><?php echo $opportunity->expectedDate;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->opportunity->description;?></th>
      <td colspan='3'><?php echo $opportunity->description;?></td>
    </tr>
  </table>

  <h4 style='padding:10px 15px;'>阶段变更记录</h4>
  <table class='table table-condensed'>
    <thead>
      <tr><th>时间</th><th>操作人</th><th>变更</th></tr>
    </thead>
    <tbody>
      <?php foreach($logs as $log):?>
      <tr>
        <td><?php echo $log->operatedDate;?></td>
        <td><?php echo $log->operator;?></td>
        <td><?php echo $lang->opportunity->stageList[$log->fromStage] . ' → ' . $lang->opportunity->stageList[$log->toStage];?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
