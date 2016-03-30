<form id="option">
    <?php foreach ($parameterss as $parameters): ?>
      <?php if ($parameters->getOptional() > 0): ?>
        <?php continue; ?>
      <?php endif; ?>      
        <label><?php echo $parameters->getParamsName() ?> </label>
        <?php if ($parameters->getParamsValue() != ''): ?>         
        <select name="<?= $parameters->getParamsName() ?>">
              <?php foreach (preg_split("/,/", $parameters->getParamsValue()) as $param): ?>
                <?php if (preg_match("/list/", $param)): ?>
                  <option id="lst" value="<?= $param ?>"><?= $param ?></option>
                <?php else: ?>
                  <option value="<?= $param ?>"><?= $param ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>          
        <?php else: ?>
          <input name="<?= $parameters->getParamsName() ?>" type="text" />
        <?php endif; ?>              
    <?php endforeach; ?>
</form>
