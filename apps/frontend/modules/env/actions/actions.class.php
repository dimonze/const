<?php

/**
 * env actions.
 *
 * @package    const
 * @subpackage env
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class envActions extends sfActions
{

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->getUser()->getAttribute('username'));
    $this->env = self::getEnv($this->getUser()->getAttribute('username'));
    $this->envArr = array();
    foreach ($this->env as $value)
    {
      foreach ($value as $key => $val)
      {
        $this->envArr[$value->env_name][$key] = $val;
      }
    }
    echo(json_encode($this->envArr));
  }

  public function getEnv($user)
  {
    return Doctrine::getTable('Env')->findBy('owner', $user);
  }

}
