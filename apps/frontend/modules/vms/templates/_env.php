<ol id="selectableEnvList">
  <?php foreach ($envsList as $value): ?>    
    <li class="ui-widget-content" id="<?= $value->getAccessVmIp() ?>"><?= $value->getAccessVmShortName() ?></li>
    <?php endforeach; ?>
</ol>