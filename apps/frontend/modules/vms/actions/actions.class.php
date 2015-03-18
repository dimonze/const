<?php

/**
 * vms actions.
 *
 * @package    const
 * @subpackage vms
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vmsActions extends sfActions
{

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('env_id'));
    $this->vms = self::getVms($request->getParameter('env_id'));
    $this->vmsArr = array();
    $i = 0;
    foreach ($this->vms as $value)
    {
      foreach ($value as $key => $val)
      {
        $this->vmsArr[$i][$key] = $val;
      }      
      $i++;
    }
    echo(json_encode($this->vmsArr));
  }

  public function getVms($access_vm_id)
  {
    return Doctrine::getTable('Vms')->findBy('access_vm_id', $access_vm_id);
  }

}
