<?php //include_component('env', 'vms');         ?>
<?php //include_component('actions', 'act');         ?>
<div id="envMenu">
  <ol id="selectable">
    <?php foreach ($envsWithVms as $value): ?>    
      <li class="ui-widget-content" id="<?= $value["access_vm"] ?>"><?= $value["env_name"] ?></li>
    <?php endforeach; ?>
  </ol>
</div>

<div id="vmMenu">
  <ol id="selectableVm" > 
    <?php foreach ($envsWithVms as $value): ?>
      <?php foreach ($value["vms"] as $vms): ?>  
        <?php $port = preg_split("/\./", $vms->getVmIp()); ?>    
        <li class="ui-widget-content" id="<?= $value["access_vm"] ?>" name="<?= sizeof($port) < 3 || strlen($port[3]) >= 3 ? 0 : $port[3] ?>" hidden="true"><?= $vms->getDescription() ?></li>
      <?php endforeach; ?>

    <?php endforeach; ?>
  </ol>
</div>

<div id="actions">
  <ol id="selectableActions" > 
    <?php foreach ($_actions as $action): ?>  
      <li class="ui-widget-content" id="<?= $action->getActionName() ?>" value="<?= $action->getAdditionalParams() ?>" hidden="true"><?= $action->getActionName() ?></li>
    <?php endforeach; ?>   
  </ol>
  <br>
  <div id="options" class="inline">
 
  </div>
</div>

<button class="start" hidden="true">Launch missile</button>
<div id='loadingmessage' style='display:none'>
  <img src='/images/ajax-loader.gif'/>
</div>

<div class="output"></div>