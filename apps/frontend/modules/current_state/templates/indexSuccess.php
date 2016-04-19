
<?php foreach ($tasks as $task): ?>
  <li id="<?= $task->getState() ?>">
      <a href='#' id="<?= $task->getId() ?>"><?= $task->getVmName() ?></a> - 
  <?= $task->getActionName() ?> - 
  <?= $task->getState() ?>
  </li>
<?php endforeach; ?>
