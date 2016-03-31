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
    $this->act = $this->getAct($this->getUser()->getAttribute('username'));
    $this->act_predef = $this->getAct('default');
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
    return Doctrine::getTable('Actions')->findOneBy('action_name', $user);
  }

  public function executeRunAct(sfWebRequest $request)
  {
    include('../lib/vendor/ssh/Net/SSH2.php');
    include('../lib/vendor/ssh/Net/SFTP.php');
    $this->actions = $_POST['actions'];
    $this->accessVmHost = $_POST['accessVmHost'];
    $this->accessVmPort = $_POST['accessVmPort'];
    $this->accessVmUser = $_POST['accessVmUser'];
    $this->accessVmPass = $_POST['accessVmPass'];
    $this->gwHost = $_POST['gwHost'];
    $this->dpsHost = $_POST['dpsHost'];
    $this->tempFile = 'd:\tmp\file.tmp';
    $this->ostype = $_POST['ostype'];
    $this->options = $_POST['options'];
    if (!array_key_exists("bitLocation", $this->options)) {
      $this->options['bitLocation'] = NULL;
    }
    $this->_output = array();
    $this->_commands = array();
    $this->errors = NULL;
    if ($this->ostype == "win32") {
      if ($this->accessVmUser == "" || $this->accessVmPass == "") {
        $this->accessVmUser = "Administrator";
        $this->accessVmPass = "installed";
      }
      $this->options['OS_TYPE'] = "Win5.2";
      $this->options['bitExt'] = "zip";
      $this->path = "/cygdrive/c/HPBSM";
      $this->extention = "bat";
      $this->tmpFolder = "/cygdrive/c/tmp";
    } else {
      if ($this->accessVmUser == "" || $this->accessVmPass == "") {
        $this->accessVmUser = "root";
        $this->accessVmPass = "installed";
      }
      $this->options['OS_TYPE'] = "Linux2.6";
      $this->options['bitExt'] = "tar.gz";
      $this->path = "/opt/HP/BSM";
      $this->extention = "sh";
      $this->tmpFolder = "/tmp";
    }
    if (!($this->ssh_ver = new Net_SSH2("$this->accessVmHost", "$this->accessVmPort"))) {
      $this->errors .= "Conection intit failed \n" . __LINE__ . __FILE__;
    }
    if (!($this->ssh_ver->login("$this->accessVmUser", "$this->accessVmPass"))) {
      $this->errors .= "Autentification failed \n" . __LINE__ . __FILE__;
    }
    if (!($this->sftp = new Net_SFTP("$this->accessVmHost", "$this->accessVmPort"))) {
      $this->errors .= "Conection intit failed \n" . __LINE__ . __FILE__;
    }
    if (!($this->sftp->login("$this->accessVmUser", "$this->accessVmPass"))) {
      $this->errors .= "Autentification failed \n" . __LINE__ . __FILE__;
    }


    $this->act = $this->getAct($this->actions);
    $action = $this->getPreparedAction($this->act);
    if ($action["edit"]) {
      $this->editFiles($action["edit"]);
    }
    if ($action["upload"]) {
      $this->uploadFiles($action["upload"]);
    }

    if ($action["sshexec"]) {
      foreach ($action["sshexec"] as $value)
      {
        if (preg_match("/;/", $value[0])) {
          $this->_commands = preg_split("/;/", $value[0]);
        } else {
          $this->_commands[] = $value[0];
        }
        $i = 0;
        foreach ($this->_commands as $val)
        {
          $this->_output[$val.$i] = $this->execCommand($val);
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

  public function checkActionType($value)
  {
    var_dump($value);
    var_dump(preg_match("/_upload/", $value));
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

  public function updateVariables($whatTrim, $actionName)
  {
    $this->specOptions = Doctrine::getTable('parameters')->getOptinalParameters($actionName);
    $value = $whatTrim;
    foreach ($this->specOptions as $opt)
    {
      $key = "/%" . $opt->getParamsName() . "%/";
      $value = preg_replace($key, $this->getOsRelatedOption($opt->getParamsValue()), $value);
    }
    foreach ($this->options as $key => $val)
    {
      $key = "/%" . $key . "%/";
      $value = preg_replace($key, $val, $value);
    }
    return $value;
  }

  public function getOsRelatedOption($option)
  {
    $result = NULL;
    $result = preg_split("/,/", $option);

    foreach ($result as $value)
    {
      if (preg_match("/" . $this->ostype . "/", $value)) {
        return preg_split("/::/", $value)[1];
      }
    }
  }

}
