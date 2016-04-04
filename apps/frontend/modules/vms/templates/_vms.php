<ol id="selectableVmList"> 
  <?php foreach ($envsWithVms as $vms): ?>
    <li class="ui-widget-content" id="<?= $vms->getAccessVmIp() ?>" name="<?= $vms->getVmPort() ?>" title="<?= $vms->getVmOs() ?>" ><?= $vms->getShortVmName() ?></li>
  <?php endforeach; ?>
</ol>
