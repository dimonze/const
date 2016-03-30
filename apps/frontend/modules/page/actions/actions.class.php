<?php

/**
 * page actions.
 *
 * @package    const
 * @subpackage page
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pageActions extends sfActions
{

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->user = $this->getUser()->getAttribute("username");
    $this->_envs = Doctrine::getTable('Env')->findSpecificEnv($this->user);
    $this->_actions = Doctrine::getTable('Actions')->findAll();
    $this->envsWithVms = array();
    $this->envsWithVms = $this->obtainEnvs($this->user, $this->_envs);
    //var_dump($this->envsWithVms);
  }
  
  public function obtainEnvs($user, $_envs)
  {
    $results = array();
    foreach ($_envs as $index => $value)
    {
      foreach ($value as $key => $val)
      {
        $results[$index][$key] = $val;
      }
      $results[$index]["vms"] = Doctrine::getTable('Vms')->findSpecificVms($user, $value->access_vm);
    }
    return $results;
  }

}
