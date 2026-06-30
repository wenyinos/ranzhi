<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='menuActions'>
  <?php commonModel::printLink('opportunity', 'browse', '', '<i class="icon-arrow-left"></i> ' . $lang->opportunity->browse, "class='btn btn-default'");?>
</div>
<div class='panel'>
  <div class='panel-heading'><strong><?php echo $lang->opportunity->funnel;?></strong></div>
  <div class='panel-body'>
    <table class='table table-bordered'>
      <thead>
        <tr>
          <th><?php echo $lang->opportunity->stage;?></th>
          <th class='w-100px'>数量</th>
          <th class='w-150px'>金额</th>
          <th>占比</th>
        </tr>
      </thead>
      <tbody>
        <?php $total = 0; foreach($funnel as $f) $total += $f->cnt;?>
        <?php foreach($lang->opportunity->stageList as $key => $label):?>
        <?php if($key == 'won' || $key == 'lost') continue;?>
        <?php $f = isset($funnel[$key]) ? $funnel[$key] : null;?>
        <tr>
          <td><strong><?php echo $label;?></strong></td>
          <td class='text-right'><?php echo $f ? $f->cnt : 0;?></td>
          <td class='text-right'><?php echo $f ? number_format($f->totalAmount, 2) : '0.00';?></td>
          <td>
            <div class='progress' style='margin:0'>
              <div class='progress-bar progress-bar-info' style='width:<?php echo $total > 0 ? round($f->cnt / $total * 100) : 0;?>%'><?php echo $total > 0 ? round($f->cnt / $total * 100) : 0;?>%</div>
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
