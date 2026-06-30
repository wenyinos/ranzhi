<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php commonModel::printLink('opportunity', 'browse', '', '<i class="icon-arrow-left"></i> ' . $lang->opportunity->browse, "class='btn btn-default'");?>
</div>
<div class='pipeline-board' style='display:flex;gap:10px;overflow-x:auto;padding:10px;'>
  <?php foreach(array('initial','demand','proposal','negotiate') as $stage):?>
  <div class='pipeline-column' style='min-width:250px;flex:1;background:#f5f5f5;border-radius:4px;padding:10px;'>
    <h4 style='margin:0 0 10px 0;padding-bottom:8px;border-bottom:2px solid #337ab7;'>
      <?php echo $lang->opportunity->stageList[$stage];?>
      <span class='badge'><?php echo isset($pipeline[$stage]) ? count($pipeline[$stage]) : 0;?></span>
    </h4>
    <?php if(isset($pipeline[$stage])):?>
    <?php foreach($pipeline[$stage] as $opp):?>
    <div class='panel panel-default' style='margin-bottom:8px;cursor:pointer;' data-url='<?php echo $this->createLink('opportunity', 'view', "id={$opp->id}");?>'>
      <div class='panel-body' style='padding:8px;'>
        <strong><?php echo $opp->name;?></strong><br/>
        <small class='text-muted'>客户 #<?php echo $opp->customer;?></small><br/>
        <span class='text-success'>¥<?php echo number_format($opp->amount, 2);?></span>
        <span class='pull-right'><?php echo $opp->probability;?>%</span>
      </div>
    </div>
    <?php endforeach;?>
    <?php endif;?>
  </div>
  <?php endforeach;?>
</div>
<?php include '../../common/view/footer.html.php';?>
