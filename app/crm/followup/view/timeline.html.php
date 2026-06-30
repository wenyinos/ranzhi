<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'><strong><?php echo $lang->followup->timeline;?></strong></div>
  <div class='panel-body'>
    <?php if(!empty($followups)):?>
    <div class='timeline'>
      <?php foreach($followups as $f):?>
      <div class='timeline-item' style='padding:10px 0;border-bottom:1px solid #eee;'>
        <div class='pull-left' style='width:80px;text-align:center;'>
          <span class='label label-default'><?php echo $lang->followup->typeList[$f->type];?></span>
        </div>
        <div style='margin-left:90px;'>
          <div><?php echo nl2br($f->content);?></div>
          <small class='text-muted'><?php echo $f->operator . ' · ' . $f->operatedDate;?></small>
          <?php if($f->nextDate):?>
          <br/><small class='text-warning'>下次跟进: <?php echo $f->nextDate . ' ' . $f->nextPlan;?></small>
          <?php endif;?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <?php else:?>
    <p class='text-muted'>暂无跟进记录</p>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
