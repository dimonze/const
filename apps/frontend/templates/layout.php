<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <a href="<?= url_for2('default', array('action' => 'logout')) ?>">Выход</a> ***
    <a href="<?= url_for2('Cron_task', array('action' => 'index')) ?>">Re-index</a>
    <h4>Username:  <?= sfContext::getInstance()->getUser()->getAttribute("username") ?></h4>    
    <?php echo $sf_content ?>    
  </body>
</html>
