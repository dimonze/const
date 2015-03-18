<?php

/**
 * env components.
 *
 * @package    const
 * @subpackage env
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class envComponents extends sfComponents
{

  public function executeVms()
  {   
    $this->env = self::getEnv($this->getUser()->getAttribute('username'));
    $this->envArr = array();
    $this->vmsArr = array();
    $i = 0;
    foreach ($this->env as $value_env)
    {
      $this->vms = self::getVms($value_env->id);
      foreach ($this->vms as $vms)
      {
        foreach ($vms as $key_vms => $val_vms)
        {
          $this->vmsArr[$i][$key_vms] = $val_vms;
        }
        $i++;
      }
      $this->envArr[$value_env->env_name]['vms'] = $this->vmsArr;   
      $this->vmsArr = array(); 
      foreach ($value_env as $key => $val)
      {
        $this->envArr[$value_env->env_name][$key] = $val;
      }      
    }
  }

  public function getEnv($user)
  {
    return Doctrine::getTable('Env')->findBy('owner', $user);
  }

  public function getVms($access_vm_id)
  {
    return Doctrine::getTable('Vms')->findBy('access_vm_id', $access_vm_id);
  }

}
