<div class="leftpart">
  <div class="vmMenu" style='display:none'>      
    <?php include_component('vms', 'vms', array('envsWithVms' => $envsWithVms)) ?>
  </div>
  <div class="actions" style='display:none'>      
    <?php include_component('actions', 'act', array('_actions' => $_actions, 'times' => $timeAvg)) ?>
  </div>  
  <div class="options"></div>  
</div>

<div class="centerPart" >
  <div class="output"><ol id="showDetails"></ol></div>
  <div class="details">    
  </div>
</div>

<div class="rigtpart">
  <div class="envMenu">      
    <?php include_component('vms', 'env', array('envsList' => $envsList)) ?>
  </div>
</div>
