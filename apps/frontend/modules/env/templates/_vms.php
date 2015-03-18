<div class="left_pain_vms" id="vms">
  <h1 class="ui-widget-header">VMs</h1>
  <div id="list_vms">
    <?php foreach ($envArr as $key => $value): ?>
    <h2><a href="#"><?= $key ?></a></h2>
    <div>
      <ul class="rectangle-list">
        
        <?php foreach ($value['vms'] as $key => $val): ?>        
        <li id=""><?= $val['vm_name'] ?>
          <p class="ip" id="<?= $val['vm_ip'] ?>" hidden></p>
          <p class="name" id="<?= $val['vm_name'] ?>" hidden></p>
        </li>
        
        <?php endforeach; ?>
        
      </ul>
    </div>
    <?php endforeach; ?>
    
  </div>
</div>
