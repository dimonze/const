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
    $this->specOptions = Doctrine::getTable('parameters')->getOptinalParameters($this->sysVars['action'], $this->sysVars['ostype']);
    $this->act = Doctrine::getTable('Actions')->findOneBy('action_name', $this->sysVars['action']);

    $this->tempFile = 'd:\tmp\file.tmp';
    $this->_output = array();
    $this->_outputStr = "";
    $this->_hideShellString = false;
    $this->_commands = array();
    $this->errors = NULL;

    if (!($this->sftp = new Net_SFTP($this->sysVars['host'], $this->sysVars['port']))) {
      $this->errors .= "Conection intit failed \n" . __LINE__ . __FILE__;
    }
    if (!($this->sftp->login($this->specOptions['ssh_user_general'], $this->specOptions['ssh_pass_general']))) {
      $this->errors .= "Autentification failed \n" . __LINE__ . __FILE__;
    }
  }

  public function executeRunAct()
  {  
    $test = new sshControl();
    var_dump($test);
    $this->initVars(filter_input_array(INPUT_POST));     
    $action = $this->getPreparedAction($this->act);    
    if ($action["edit"]) { $this->editFiles($action["edit"]); }
    if ($action["upload"]) { $this->uploadFiles($action["upload"]); }    
    if ($action["sshexec"]) { 
      $i = 0;      
      foreach ($action["sshexec"] as $value)
      {
        if (preg_match("/;/", $value[0])) {$this->_commands = preg_split("/;/", $value[0]);} 
        else { $this->_commands[] = $value[0]; }        
        foreach ($this->_commands as $val)
        {
          if (array_key_exists("_hideShellString", $action)) {
            $this->_outputStr .= $this->execCommand($val) . "\n";            
            $this->_hideShellString = true;
          } else { $this->_output["[" . $this->execCommand("pwd") . "]# " . $val . $i] = $this->execCommand($val); }
          $i++;
        }
      }
    }
  }

  public function getPreparedAction($action)
  {
    $result = preg_split("/:\|:/", preg_replace('/\n/', '', $action->sample_act));
    $output = array();
    $output["edit"] = NULL;
    $output["upload"] = NULL;
    $output["sshexec"] = NULL;
    $textVal = NULL;
    unset($result[count($result) - 1]);
    foreach ($result as $value)
    {
      $value = $this->updateVariables($value, $this->actions);
      $textVal .= $value;
      switch ($value)
      {
        case!!(preg_match("/_edit/", $value)):
          preg_match("/\((.+)\)/", $value, $value);
          $output["edit"][] = preg_split("/;/", preg_replace('/(\n+)/', '', $value[1]));
          break;
        case!!(preg_match("/_hideShellString/", $value)):
          preg_match("/\((.+)\)/", $value, $value);
          $output["_hideShellString"] = true;
          break;
        case!!(preg_match("/_upload/", $value)):
          preg_match('/\((.+)\)/', $value, $value);
          $output["upload"][] = preg_split("/,/", preg_replace('/(\n+)|(\s+)/', '', $value[1]));
          break;
        case!!(preg_match("/_packageLocationFinal/", $value)):
          preg_match('/\((.+)\)/', $value, $value);
          $output["_packageLocationFinal"] = preg_split("/;/", preg_replace('/(\n+)/', '', $value[1]));
          break;
        case!!(preg_match("/_sshexec/", $value)):
          preg_match('/\((.+)\)/', $value, $m);
          $output["sshexec"][] = preg_replace('/\n/', '', $m[1]);
          break;
        default:
          break;
      }
    }
    if (preg_match("/_packageLocationFinal/", $textVal)) {
      foreach ($output["_packageLocationFinal"] as $value)
      {

        if (preg_match("/" . $this->options['VERSION'] . "/", preg_split('/,/', $value)[1])) {
          $this->options['bitLocation'] = str_replace(array("\r", "\n"), "", $this->trimValues(preg_split('/,/', $value)[0]));
          break;
        }
      }
    }
    if (preg_match("/_sshexec/", $textVal)) {
      foreach ($output["sshexec"] as $key => $value)
      {
        $output["sshexec"][$key] = str_replace(array("\r", "\n"), "", $this->trimValues(array($value)));
        if (array_key_exists("VERSION", $this->options)) {
          $output["sshexec"][$key] = preg_replace("/%package%/", $this->options['bitLocation'], $output["sshexec"][$key]);
        }
      }
    }
    return $output;
  }

  public function editFiles(array $whatEdit)
  {
    foreach ($whatEdit as $value)
    {
      $filePath = trim(array_shift($value));
      $file = preg_split("/\n/", $this->sftp->get($filePath));
      foreach ($value as $key)
      {
        $key = preg_split("/,/", $key);
        foreach ($file as &$str)
        {
          if ($str == ltrim($key[0])) {
            $str = ltrim($key[1]);
          }
        }
      }
      $fp = fopen($this->tempFile, "w+");
      foreach ($file as $string)
      {
        fwrite($fp, $string . "\n");
      }
      fclose($fp);
      $this->sftp->put($filePath, $this->tempFile, NET_SFTP_LOCAL_FILE);
    }
  }

  public function uploadFiles($whatUpload)
  {
    foreach ($whatUpload as $value)
    {
      $this->sftp->put(ltrim($value[1]), ltrim($value[0]), NET_SFTP_LOCAL_FILE);
    }
  }

  public function execCommand($whatExec)
  {
    if (!($this->ssh_ver = new Net_SSH2($this->sysVars['host'], $this->sysVars['port']))) {
      $this->errors .= "Conection intit failed \n" . __LINE__ . __FILE__;
    }
    if (!($this->ssh_ver->login($this->specOptions['ssh_user_general'], $this->specOptions['ssh_pass_general']))) {
      $this->errors .= "Autentification failed \n" . __LINE__ . __FILE__;
    }
    
    return $this->ssh_ver->exec($whatExec);
  }

  public function trimValues($whatTrim)
  {
    $output = NULL;
    foreach ((array) $whatTrim as &$val)
    {
      $value = preg_split("/;/", rtrim(preg_replace('/\n/', '', trim($val)), ';'));
      foreach ($value as $str)
      {
        $output .= trim(preg_replace('/\n/', '', $str . ';'), "\n\r");
      }
      $val = $output;
    }
    return $whatTrim;
  }

  public function updateVariables($whatTrim)
  {   
    foreach ($this->specOptions as $key => $val)
    {
      $key = "/%" . $key . "%/";
      $whatTrim = preg_replace($key, $val, $whatTrim);
    }
    foreach ($this->options as $key => $val)
    {
      $key = "/%" . $key . "%/";
      $whatTrim = preg_replace($key, $val, $whatTrim);
    }
    return $whatTrim;
  }

}
