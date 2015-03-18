<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.png" />
    <?php include_stylesheets() ?>
    <link href="/sfDoctrinePlugin/css/default.css" type="text/css" rel="stylesheet" />
  </head>
  <body>

    <div id="sf_admin_container">

      <div style="position: fixed; top: 50%; left: 50%; margin-top: -100px; margin-left: -150px;">
        <form action="<?php echo url_for('default/login') ?>" method="post">
          <table>
            <thead>
              <tr><th colspan="2">Login into the darkness</th></tr>
            </thead>
            <tfoot>
              <tr><td></td><td><input type="submit" value="Вход" /></td></tr>
            </tfoot>
            <tbody>
              <tr><td>Login:</td><td><?php echo $form['login'] ?></td></tr>
              <tr><td>Pass:</td><td><?php echo $form['pass'] ?></td></tr>
              <?php if($_failed): ?>
              <script>alert("Login incorrect!");</script>
              <?php endif; ?>
            </tbody>
          </table>
        </form>
      </div>

    </div>
    
  </body>
</html>