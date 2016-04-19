<?php

/**
 * actions actions.
 *
 * @package    const
 * @subpackage actions
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class actionsActions extends sfActions
{

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    
  }

  private function initVars($post)
  {
    $this->sysVars = array_key_exists('systemVariables', $post) ? $post['systemVariables'] : NULL;
    $this->options = array_key_exists('options', $post) ? $post['options'] : array();
    $this->node = Doctrine::getTable('vms')->findOneBy('vm_name', $this->sysVars['vm_name']);
    $this->options["GW_HOST"] = $this->node->getVmHost(); 
    $this->specOptions = Doctrine::getTable('parameters')->getOptinalParameters($this->sysVars['action'], $this->node->getVmOs());
    $this->act = Doctrine::getTable('Actions')->findOneBy('action_name', $this->sysVars['action']);
    $this->strHash = '1234567890qazwsxedcRFVtgbNHYUJMIKLIO';
  }

  public function executeRunAct()
  {    
    $this->initVars(filter_input_array(INPUT_POST));
    $action = $this->act->getPreparedAction(array_merge($this->options, $this->specOptions));
    $sshCtl = new sshControl($action, $this->node, 
            $this->specOptions['rslt_path'].str_shuffle($this->strHash), 
            $this->specOptions['rslt_path'].str_shuffle($this->strHash),
            $this->specOptions['rslt_path'].str_shuffle($this->strHash));
    $sshCtl->execAll();    
    $task = new Current_state;
    $task->setVmName($this->node->getVmName());
    $task->setActionName($this->act->getActionName());
    $task->setOutputPath($sshCtl->getSshOutputPath());
    $task->setOutput($sshCtl->getErrors());
    $task->setState('in progress');
    $task->setPostAction(serialize($sshCtl->getAction()));
    $task->setCleanupCommand($sshCtl->getSshCleanupString());
    $task->setOwner(sfContext::getInstance()->getUser()->getAttribute("username"));
    $task->save();
  }

}
