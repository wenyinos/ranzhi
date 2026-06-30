<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php commonModel::printLink('opportunity', 'create', '', '<i class="icon-plus"></i> ' . $lang->opportunity->create, "class='btn btn-primary' data-toggle='modal' data-width='700'");?>
  <?php commonModel::printLink('opportunity', 'funnel', '', '<i class="icon-filter"></i> ' . $lang->opportunity->funnel, "class='btn btn-default'");?>
  <?php commonModel::printLink('opportunity', 'pipeline', '', '<i class="icon-columns"></i> ' . $lang->opportunity->pipeline, "class='btn btn-default'");?>
</div>
<div class='main panel'>
  <table class='table table-bordered table-hover table-striped tablesorter table-data'>
    <thead>
      <tr class='text-center'>
        <th class='w-60px'><?php echo $lang->opportunity->id;?></th>
        <th><?php echo $lang->opportunity->name;?></th>
        <th class='w-100px'><?php echo $lang->opportunity->customer;?></th>
        <th class='w-100px'><?php echo $lang->opportunity->stage;?></th>
        <th class='w-100px'><?php echo $lang->opportunity->amount;?></th>
        <th class='w-60px'> <?php echo $lang->opportunity->probability;?></th>
        <th class='w-80px'> <?php echo $lang->opportunity->owner;?></th>
        <th class='w-120px'><?php echo $lang->opportunity->expectedDate;?></th>
        <th class='w-100px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($opportunities as $opp):?>
      <tr class='text-center' data-url="<?php echo $this->createLink('opportunity', 'view', "id={$opp->id}");?>">
        <td><?php echo $opp->id;?></td>
        <td class='text-left'><?php echo $opp->name;?></td>
        <td><?php echo $opp->customer;?></td>
        <td><span class='label label-info'><?php echo $lang->opportunity->stageList[$opp->stage];?></span></td>
        <td class='text-right'><?php echo $opp->amount;?></td>
        <td><?php echo $opp->probability;?>%</td>
        <td><?php echo $opp->owner;?></td>
        <td><?php echo $opp->expectedDate;?></td>
        <td>
          <?php commonModel::printLink('opportunity', 'edit', "id=$opp->id", $lang->edit, "data-toggle='modal' data-width='700'");?>
          <?php commonModel::printLink('opportunity', 'delete', "id=$opp->id", $lang->delete, "class='reloadDeleter'");?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show();?></div>
</div>
<?php include '../../common/view/footer.html.php';?>
