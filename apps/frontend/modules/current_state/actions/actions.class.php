<?php

/**
 * current_state actions.
 *
 * @package    const
 * @subpackage current_state
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class current_stateActions extends sfActions
{

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    self::chekcStatus();

    $this->tasks = Doctrine::getTable('Current_state')->getAllTasks(sfContext::getInstance()->getUser()->getAttribute("username"));
  }

  public function executeResult(sfWebRequest $request)
  {
    self::chekcStatus();
    $id = filter_input(INPUT_POST, 'id');
    $this->task = Doctrine::getTable('Current_state')->findOneBy('id', $id);
    $this->node = Doctrine::getTable('vms')->findOneBy('vm_name', $this->task->getVmName());
    $ssh = new sshControl(NULL, $this->node);
    $this->output = $ssh->checkExecutionStatus($this->task->getOutputPath());
    
  }

  public function chekcStatus()
  {
    $this->tasks = Doctrine::getTable('Current_state')->getInprogressTasks(sfContext::getInstance()->getUser()->getAttribute("username"));


    foreach ($this->tasks as $task)
    {
      $this->node = Doctrine::getTable('vms')->findOneBy('vm_name', $task->getVmName());
      $ssh = new sshControl(NULL, $this->node);
      $result = $ssh->checkExecutionStatus($task->getOutputPath());
      if ($ssh->checkExecutionStatusTrue($task->getOutputPath())) {
        $task->setState('done');
        $ssh->cleanUpSsh($task->getCleanupCommand());
        $task->setExecutionTime($ssh->checkExecutionTime($task->getOutputPath()));
        if (strlen($task->getPostAction()) > 0) {
          $ssh->execAllPost(unserialize($task->getPostAction()));
        }
        $task->save();
      }else if(strlen($task->getOutput()) > 1){
        $task->setState('failed');
        $task->save();
      } else {
        $task->save();
      }
    }
  }

}
