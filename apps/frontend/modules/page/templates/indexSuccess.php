<?php //include_component('env', 'vms');            ?>
<?php //include_component('actions', 'act');            ?>
<div id="content">
  <div id="rigtpart">
    <div id="envMenu">
      <ol id="selectable">
        <?php foreach ($envsWithVms as $value): ?>    
          <li class="ui-widget-content" id="<?= $value["access_vm"] ?>"><?= $value["env_name"] ?></li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
  <div id="leftpart">
    <div id="vmMenu" hidden="true">
      <ol id="selectableVm"> 
        <?php foreach ($envsWithVms as $value): ?>
          <?php foreach ($value["vms"] as $vms): ?>  
            <?php $port = preg_split("/\./", $vms->getVmIp()); ?>    
            <li class="ui-widget-content" id="<?= $value["access_vm"] ?>" name="<?= sizeof($port) < 3 || strlen($port[3]) >= 3 ? 0 : $port[3] ?>" title="<?= $vms->getVmOs() ?>" hidden="true"><?= $vms->getShortVMDescr() ?></li>
          <?php endforeach; ?>

        <?php endforeach; ?>
      </ol>
    </div>

    <div id="actions" hidden="true">
      <ol id="selectableActions" > 
        <?php foreach ($_actions as $action): ?>  
          <?php
          $param = $action->getAdditionalParams() + $action->getWithoutGeneralParams();
          ?>
          <li class="ui-widget-content" id="<?= $action->getActionName() ?>" value="<?= $param ?>"title="<?= $action->getDescription() ?>" hidden="true"><?= $action->getActionName() ?></li>
        <?php endforeach; ?>   
      </ol>

    </div>

    <button class="start" hidden="true">Launch missile</button>
    <div id='loadingmessage' style='display:none'>
      <img src='/images/ajax-loader.gif'/>  
    </div>

    <div id="options">

    </div>
  </div>
  <div id="centerPart">
    <div class="output"></div>
  </div>
</div>