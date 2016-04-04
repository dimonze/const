<ol id="selectableActionsList" > 
  <?php foreach ($_actions as $action): ?>  
    <li class="ui-widget-content" id="<?= $action->getActionName() ?>" value="<?= $action->addGenerallParams() ?>"title="<?= $action->getDescription() ?>"><?= $action->getActionName() ?>
      &nbsp;<img class="start" title="Run" src='./images/play_button.png'/>
      &nbsp;<img class="loadingmessage" src='./images/ajax-loader_small.gif'/>        
    </li>
    <?php endforeach; ?>   
</ol>