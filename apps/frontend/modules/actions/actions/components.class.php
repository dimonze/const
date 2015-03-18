<?php

/**
 * env components.
 *
 * @package    const
 * @subpackage actions
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class actionsComponents extends sfComponents
{

  public function executeAct()
  {   
    $this->act = self::getAct($this->getUser()->getAttribute('username'));
    $this->act_predef = self::getAct('default');
    $this->actArr = array();
    foreach ($this->act as $value)
    {
      foreach ($value as $key => $val)
      {
        $this->actArr[$value->action_name][$key] = $val;        
      }
    }
    foreach ($this->act_predef as $value)
    {
      foreach ($value as $key => $val)
      {
        $this->actArr[$value->action_name][$key] = $val;        
      }
    }
  }

  public function getAct($user)
  {
    return Doctrine::getTable('Actions')->findBy('owner', $user);
  }

}
