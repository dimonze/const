<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sshControl
 *
 * @author dhimenes
 */
class sshControl
{

  private $action = FALSE;
  private $node = FALSE;
  private $resultFile = FALSE;
  private $scriptFile = FAlSE;
  private $tmpFile = FAlSE;
  private $sftp = FALSE;
  private $ssh = FALSE;
  private $errors = FALSE;
  private $nohup = 'nohup ';
  private $tempFile = FALSE;

  public function __construct($act, $node, $resultFile = '/tmp/result.txt', $scriptFile = '/root/script.sh', $tmpFile = '/root/tmp.sh')
  {
    $this->action = $act;
    $this->node = $node;
    $this->resultFile = $resultFile;
    $this->scriptFile = $scriptFile;
    $this->tmpFile = $tmpFile;
    $this->tempFile = 'd:\tmp\file.tmp';
    $this->initConnection();
  }

  private function initConnection()
  {
    if (!($this->sftp = new Net_SFTP($this->node->getAccessVmIp(), $this->node->getVmPort()))) {
      $this->errors .= "";
    }
    if (!($this->sftp->login($this->node->getSshUser(), $this->node->getSshPass()))) {
      $this->errors .= "";
    }
    if (!($this->ssh = new Net_SSH2($this->node->getAccessVmIp(), $this->node->getVmPort()))) {
      $this->errors .= "\nConection intit failed \n";
    }
    if (!($this->ssh->login($this->node->getSshUser(), $this->node->getSshPass()))) {
      $this->errors .= "\nAutentification failed \n";
    }
  }

  public function execAll($ostype = null)
  {
    foreach ($this->action as $key => $value)
    {
      if ($key == 'upload') {
        $this->execUpload();
      } else if ($key == 'edit') {
        $this->execEdit();
      } else if ($key == 'sshexec') {
        $this->execSsh();
      }
    }
  }

  public function execAllPost($act)
  {
    $this->action = $act;
    foreach ($this->action as $key => $value)
    {
      if ($key == 'editPost') {
        $this->execEditPost();
      } else if ($key == 'uploadPost') {
        $this->execUploadPost();
      }
    }
  }

  public function getAction()
  {
    return $this->action;
  }

  public function execEdit()
  {
    if (!array_key_exists('edit', $this->action)) {
      return;
    }
    foreach ($this->action['edit'] as $value)
    {
      $value = preg_split('/;/', $value);
      $filePath = trim(array_shift($value));
      $test = $this->sftp->get($filePath);
      $file = array();
      $file = preg_split("/\n/", $test);
      $file_length = count($file);

      foreach ($value as $key)
      {
        if (!preg_match('/,/', $key)) {
          continue;
        }
        $key = preg_split("/,/", $key);
        $file = $this->replaceString($file, $key);
      }
      $fp = fopen($this->tempFile, "w");
      foreach ($file as $string)
      {   
        if ($file_length != 1) {
          fwrite($fp, $string. "\n");
        } else {
          fwrite($fp, $string);
        }
          $file_length--;
      }
      fclose($fp);    
      sleep(5);
      $this->sftp->put($filePath, $this->tempFile, NET_SFTP_LOCAL_FILE);
    }
  }

  public function execEditPost()
  {
    if (!array_key_exists('editPost', $this->action)) {
      return;
    }
    foreach ($this->action['editPost'] as $value)
    {
      $value = preg_split('/;/', $value);
      $filePath = trim(array_shift($value));
      $file = preg_split("/\n/", $this->sftp->get($filePath));
      foreach ($value as $key)
      {
        if (!preg_match('/,/', $key)) {
          continue;
        }
        $key = preg_split("/,/", $key);
        $file = $this->replaceString($file, $key);
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

  private function replaceString($file, $key)
  {
    foreach ($file as &$str)
    {
      if (trim($str) == trim($key[0])) {
        $str = trim($key[1]);
      }
    }
    return $file;
  }

  public function execUpload()
  {
    if (!array_key_exists('upload', $this->action)) {
      return;
    }
    foreach ($this->action['upload'] as $value)
    {
      $value = preg_split('/,/', $value);
      $this->sftp->put(trim($value[1]), trim($value[0]), NET_SFTP_LOCAL_FILE);
    }
  }

  public function execUploadPost()
  {
    if (!array_key_exists('uploadPost', $this->action)) {
      return;
    }
    foreach ($this->action['uploadPost'] as $value)
    {
      $value = preg_split('/,/', $value);
      $this->sftp->put(trim($value[1]), trim($value[0]), NET_SFTP_LOCAL_FILE);
    }
  }

  public function getErrors()
  {
    return $this->errors;
  }

  public function execSsh()
  {
    if (!array_key_exists('sshexec', $this->action)) {
      return;
    }
    $this->prepareSshCommand();
    $this->ssh->exec($this->action['sshexec']);
  }

  private function prepareSshCommand()
  {

    $this->action['sshexec'] = $this->addNohup();
  }

  public function getSshCleanupString()
  {
    return 'rm -rf ' . $this->scriptFile . ' ' . $this->tmpFile;
  }

  public function getSshOutputPath()
  {
    return $this->resultFile;
  }

  public function addNohup()
  {
    $temp = false;
    $out = false;
    foreach ($this->action['sshexec'] as $value)
    {
      $comands = preg_split('/;/', $value);
      foreach ($comands as $value)
      {
        if (strlen(trim($value)) < 1) {
          continue;
        }
        $temp .= trim($value) . ' >> ' . $this->resultFile . ' 2>&1; \n';
      }
      $out .= $temp;
    }

    $out = $out . 'echo COMMAND FINISHED >> ' . $this->resultFile . ' 2>&1;\n';

    $out = 'date +\"%m/%d/%y %T\" > ' . $this->resultFile . ' 2>&1;\n' . $out . 'date +\"%m/%d/%y %T\" >> ' . $this->resultFile . ' 2>&1;';

    $out = 'echo -e "' . $out . '" > ' . $this->scriptFile . '; chmod 755 ' .
            $this->scriptFile . '; echo -e "' .
            $this->nohup .
            $this->scriptFile . '> /dev/null 2>&1 &\n" > ' .
            $this->tmpFile . '; chmod 755 ' .
            $this->tmpFile . '; ' .
            $this->tmpFile;

    return $out;
  }

  public function checkExecutionStatus($path)
  {
    return $this->sftp->get($path);
  }

  public function checkExecutionTime($path)
  {
    $output = $this->sftp->get($path);
    $file = preg_split("/\n/", $output);
    array_pop($file);
    $firstDate = array_shift($file);
    $lastDate = array_pop($file);
    $datetime1 = new DateTime(trim($firstDate));
    $datetime2 = new DateTime(trim($lastDate));
    $interval = $datetime1->diff($datetime2);
    $time = $interval->format('%s') + ($interval->format('%i') * 60) + (($interval->format('%h') * 60) * 60);
    return $time;
  }

  public function checkExecutionStatusTrue($path)
  {
    $output = $this->sftp->get($path);
    $file = preg_split("/\n/", $output);
    foreach ($file as $string)
    {
      if (preg_match('/COMMAND FINISHED/', $string)) {
        return true;
      }
    }
    return false;
  }

  public function cleanUpSsh($path)
  {
    $this->ssh->exec($path);
  }

}
