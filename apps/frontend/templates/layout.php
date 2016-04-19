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
    -=<a href="<?= url_for2('default', array('action' => 'logout')) ?>">Logout</a>=-
    -=<u class="reindex" href="#">Re-index</u>=-<img class="loadsmall" src='/images/ajax-loader_small.gif'/>   
    -=<a class="admin" href="/backend.php">Admin Panel</a>=- 
    -=<u class="checkTasksState" href="#">Check Tasks State</u>=-<img class="loadsmallState" src='/images/ajax-loader_small.gif'/>   
    <h4>Username:  <?= sfContext::getInstance()->getUser()->getAttribute("username") ?></h4>   
    <div class="content">
      <?php echo $sf_content ?>   
    </div>
  </body>
</html>
