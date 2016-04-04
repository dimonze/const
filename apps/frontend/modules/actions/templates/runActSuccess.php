<p>  
  <?php if (!$_hideShellString): ?>
    <?php $i = 0; ?>  
    <?php foreach ($_output as $key => $value): ?>
      <?php if ($value != "" && $value != " "): ?>
        <?php $key = substr($key, 0, -(strlen($i))); ?>
        <?= $key ?><pre><?= $value ?></pre>
      <?php endif; ?>
      <?php $i++; ?>
    <?php endforeach; ?>
  <?php else: ?>
  <pre><?= $_outputStr ?></pre>
<?php endif; ?>
</p>