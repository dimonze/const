<?php

/**
 * cron_task actions.
 *
 * @package    const
 * @subpackage cron_task
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cron_taskActions extends sfActions
{

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $currentDir = getcwd();
    chdir(sfConfig::get('sf_root_dir'));
    $task = new updateEnvsTask($this->dispatcher, new sfFormatter());
    $rc = $task->run(array(), array('users' => sfContext::getInstance()->getUser()->getAttribute("username")));
    chdir($currentDir);
    sfContext::switchTo('frontend');
  }

}
