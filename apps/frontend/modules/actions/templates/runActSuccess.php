<p>
  <?php foreach ($_output as $key => $value): ?>
    <?php if ($key != "" && $key != " "): ?>
      <?php $key = substr_replace($key, "", -1); ?>
      shell:> <?= $key ?><pre><?= $value ?></pre>
  <?php endif; ?>
<?php endforeach; ?>
</p>