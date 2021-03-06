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
    <ul class="backend_nav">
      <li<?= $sf_params->get('module') == 'users' ? ' class="current"' : '' ?>>
        <?= link_to('Users', '@users') ?>
      </li>
      <li<?= $sf_params->get('module') == 'vms' ? ' class="current"' : '' ?>>
        <?= link_to('VMs', '@vms') ?>
      </li>
      <li<?= $sf_params->get('module') == 'actions' ? ' class="current"' : '' ?>>
        <?= link_to('Actions', '@actions') ?>
      </li>
      <li<?= $sf_params->get('module') == 'parameters' ? ' class="current"' : '' ?>>
        <?= link_to('Parameters', '@parameters') ?>
      </li>
      <li<?= $sf_params->get('module') == 'templates' ? ' class="current"' : '' ?>>
        <?= link_to('Templates', '@templates') ?>
      </li>
      <li<?= $sf_params->get('module') == 'cron_task' ? ' class="current"' : '' ?>>
        <?= link_to('Scheduler', '@cron_task') ?>
      </li>
      <li<?= $sf_params->get('module') == 'current_state' ? ' class="current"' : '' ?>>
        <?= link_to('Running_actions', '@current_state') ?>
      </li>
      <li style="font-style: italic;">
        <?= link_to('Logout', 'default/logout') ?>
      </li>
      <li style="font-style: italic;">
        <?= link_to('Site', '/') ?>
      </li>
      <li style="font-style: italic; position: absolute; right: 0px; margin-right: 15px;">
        Welcome, <strong><?= $sf_user->getAttribute('username') ?></strong>!
      </li>
    </ul>
    <?php echo $sf_content ?>
  </body>
</html>
