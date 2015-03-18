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
    $this->forward404Unless($this->getUser()->getAttribute('username'));
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
    echo json_encode($this->actArr);
  }

  public function getAct($user)
  {
    return Doctrine::getTable('Actions')->findBy('owner', $user);
  }

  public function execAct()
  {
    //Connect SSH class
    include('../lib/vendor/ssh/Net/SSH2.php');
    //Connect SFTP class
    //include('Net/SFTP.php');

    $ssh_ver = new Net_SSH2('mambonet-061-01-av.hpswlabs.adapps.hp.com', 12256);
    $ssh_ver->login('root', 'installed');
    //$sftp = new Net_SFTP("$host", "$port");
    $this->act[0]->sample_act = preg_replace('/%path%/', 'ftp://boda.deu.hp.com/pub/BBN.BSMINT_Zabbix/release/02.00.031/ProductImage-HPBsmIntZabbix-02.00.031-Linux2.6_64-release.tar', $this->act[0]->sample_act);

    $ver = $ssh_ver->exec($this->act[0]->sample_act);
    var_dump($ver);
    //return Doctrine::getTable('Actions')->findBy('action_name', $act);
  }

}
