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
    $this->user = Doctrine::getTable('users')->findOneBy('user', sfContext::getInstance()->getUser()->getAttribute("username"));
    $this->envsList = Doctrine::getTable('vms')->getEnvList($this->user->user);
    $this->envsWithVms = Doctrine::getTable('vms')->getDynamicVms($this->user->user);
    $this->_actions = Doctrine::getTable('actions')->getAvailbleUserAct($this->user->user, $this->user->show_public_actions);
  }

}
