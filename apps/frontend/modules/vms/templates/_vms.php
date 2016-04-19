<ol id="selectableVmList"> 
  <?php foreach ($envsWithVms as $vms): ?>
  <li class="ui-widget-content" id="<?= $vms->getAccessVmIp() ?>" name="<?= $vms->getVmName() ?>" title="<?= $vms->getVmOs() ?>" >
    <?= $vms->getShortVmName() ?>
    <a href="http://<?= $vms->getAccessVmFullName() ?>:180">Link</a>
  </li>
  <?php endforeach; ?>
</ol>
