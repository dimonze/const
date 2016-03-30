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
    //
        // Remember the current dir and change it to sf root
    $currentDir = getcwd();
    chdir(sfConfig::get('sf_root_dir'));

// Instantiate the task and run it
    $task = new updateEnvsTask($this->dispatcher, new sfFormatter());
    $rc = $task->run(array(), array('users' => $request["user"]));
  

// Restore original environment, change back to original directory
// Switch the context back to where it was
    chdir($currentDir);
    sfContext::switchTo('frontend');
    $this->redirect('homepage');
  }

  public function updateTask($user)
  {
    // Remember the current dir and change it to sf root
    $currentDir = getcwd();
    chdir(sfConfig::get('sf_root_dir'));

// Instantiate the task and run it
    $task = new updateEnvsTask($this->dispatcher, new sfFormatter());
    $rc = $task->run(array('users' => $user));
    

// Restore original environment, change back to original directory
// Switch the context back to where it was
    //chdir($currentDir);
   // sfContext::switchTo('AppName');
  }

}
