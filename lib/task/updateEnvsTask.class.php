<?php

class updateEnvsTask extends sfBaseTask
{

  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
        new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
        new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
        new sfCommandOption('users', null, sfCommandOption::PARAMETER_REQUIRED, 'The users for which we need update envs, separate by "::"', '_all'),
            // add your own options here
    ));

    $this->namespace = 'project';
    $this->name = 'updateEnvs';
    $this->briefDescription = 'Update the enviroment for all users';
    $this->detailedDescription = <<<EOF
    
The [updateEnvs|INFO] task does things.
            Blah
Call it with:
            Blah
  [php symfony updateEnvs|INFO]
EOF;

//    $this->_user = $this->getUser()->getAttribute("username");
//    $this->_users = Doctrine::getTable('Users')->findOneBy("user", "$this->_user");
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $this->sshUser = array();
    $this->sshPass = array();
    $this->sshAV = array();
    $this->envs = array();
    if ($options['users'] == "_all") {
      $this->_users = Doctrine::getTable('Users')->findAll();
    } else {
      $this->_users = Doctrine::getTable('Users')->findSpecificUsers(preg_split("/\:\:/", $options['users']));
    }
    foreach ($this->_users as $user)
    {
      if (strlen($user->full_name) < 3) {
        continue;
      }
      $oldVms = Doctrine::getTable('Vms')->getDynamicVms($user->user);
      foreach ($oldVms as $vms)
      {
        $this->sshUser[$vms->getVmName()] = $vms->getSshUser();
        $this->sshPass[$vms->getVmName()] = $vms->getSshPass();
        $this->sshAV[$vms->getVmName()] = $vms->getAccessVmFullName();
        $vms->delete();
      }
      $this->envs = $this->getUpdatedEnvs($user);
      foreach ($this->envs as $_env)
      {
        foreach ($_env["vms"] as $_vms)
        {
          $vms = new Vms;
          if (array_key_exists($_vms["name"], $this->sshAV)) {
            $vms->setAccessVmFullName($this->sshAV[$_vms["name"]]);
          }
          $vms->setAccessVmShortName($_env["shortName"]);
          $vms->setAccessVmIp($_env["accessvm"]);
          $vms->setAccessVmHostedOn($_env["hostedOn"]);
          $vms->setVmName($_vms["name"]);
          $name = preg_split("/:/", $_vms["host"]);
          $vms->setVmHost($name[0]);
          if (preg_match("/\((.*?)\)/", $name[1], $ip)) {
            $vms->setVmIp($ip[1]);
          }
          $port = preg_split("/\./", $vms->getVmIp());
          if (array_key_exists(3, $port)) {
            $vms->setVmPort('122' . $port[3]);
          }
          $vms->setVmOs($_vms["type"]);
          if (array_key_exists($_vms["name"], $this->sshUser)) {
            if (strlen($this->sshUser[$_vms["name"]]) < 3) {
              if ($_vms["type"] != 'linux') {
                $vms->setSshUser('Administrator');
                $vms->setSshPass('installed');
              } else {
                $vms->setSshUser('root');
                $vms->setSshPass('installed');
              }
            } else {
              $vms->setSshUser($this->sshUser[$_vms["name"]]);
              $vms->setSshPass($this->sshPass[$_vms["name"]]);
            }
          } else {
            if ($_vms["type"] != 'linux') {
              $vms->setSshUser('Administrator');
              $vms->setSshPass('installed');
            } else {
              $vms->setSshUser('root');
              $vms->setSshPass('installed');
            }
          }
          $vms->setOwner($user->user);
          $vms->save();
        }
      }
    }
  }

  protected function getUpdatedEnvs($user)
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_COOKIESESSION, true);
    curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookies.txt');
    curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookies.txt');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3');
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    $post = "email=dmitriy.himenes%40hpe.com&password=D67y12172&submit=Login";
    curl_setopt($curl, CURLOPT_URL, 'https://tcportal.deu.hp.com/cgi-bin/login.pl');
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    $html = curl_exec($curl);
    curl_setopt($curl, CURLOPT_URL, 'https://tcportal.deu.hp.com/cgi-bin/home.pl');
    $html = curl_exec($curl);
    curl_setopt($curl, CURLOPT_URL, 'https://tcportal.deu.hp.com/cgi-bin/list_venvs.pl?list=true&noaction=true');
    curl_setopt($curl, CURLOPT_POST, 0);
    $html = curl_exec($curl);
    $_envs = $this->getEnvs($html, $user); //Retrive env names for current user
    foreach ($_envs as $key => $value)
    {
      curl_setopt($curl, CURLOPT_URL, 'https://tcportal.deu.hp.com/cgi-bin/list_venvs_dc3.pl?name=' . $value["shortName"]);
      $html = curl_exec($curl);
      $_vms = $this->getVms($html);
      $_envs[$key]['accessvm'] = $_vms["accessvm"];
      unset($_vms["accessvm"]);
      $_envs[$key]['vms'] = $_vms;
    }
    return $_envs;
  }

  protected function getEnvs($contents, $user)
  {
    $_envs = array();
    preg_match_all("'<tr class=\"display_body\">(.*?)</tr>'si", $contents, $result);

    foreach ($result[1] as $value)
    {
      $cur_env = array();
      if (preg_match("/" . $user->full_name . "/", $value)) {
        preg_match_all("'<td class=\"display_row\">(.*?)</td>'si", $value, $res);
        preg_match("'<div class=\"display_status\">(.*?)</div>'si", $value, $descr);

        $res[1][] = $descr[1];
        $res[1][0] = preg_replace("'<div(.*?)</div>'si", "", $res[1][0]);
        $res[1][0] = preg_replace('/\n[ \t]+/', '', strip_tags($res[1][0]));

        $cur_env["shortName"] = $res[1][0];
        $cur_env["hostedOn"] = $res[1][1];
        $cur_env["owner"] = $res[1][2];
        $cur_env["fullName"] = $res[1][3];
        $_envs[] = $cur_env;
      }
    }
    return $_envs;
  }

  protected function getVms($contents)
  {
    $out = array();
    preg_match("'<table class=\"sortable\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">(.*?)</table>'si", $contents, $result);
    preg_match_all("'<tr>(.*?)</tr>'si", $result[1], $result);

    foreach ($result[1] as $value)
    {
      $cur_vms = array();
      preg_match_all("'<td class=\"display_row\">(.*?)</td>'si", $value, $result);
      if (preg_match_all("/win32/", $result[1][0])) {
        $result[1][0] = "win32";
      } else if (preg_match_all("/linux/", $result[1][0])) {
        $result[1][0] = "linux";
      } else {
        $result[1][0] = "disabled";
      }

      $result[1][1] = preg_replace("'<div(.*?)</div>'si", "", $result[1][1]);
      $result[1][1] = preg_replace("/<br>/", ":", $result[1][1]);
      $result[1][1] = preg_replace("'[\n]+[ \t]+'", '', strip_tags($result[1][1]));
      //$result[1][1] = preg_replace("'\((.*?)\)'si", '', $result[1][1]);

      $result[1][2] = preg_replace("'<\!--(.*?)-->'si", "", $result[1][2]);
      $result[1][2] = preg_replace("'[\n]+[ \t]+'", '', preg_replace("'<div(.*?)/div>'si", "", $result[1][2]));



      $cur_vms["type"] = $result[1][0];
      $cur_vms["host"] = $result[1][1];
      $cur_vms["name"] = $result[1][2];
      $cur_vms["usedRam"] = $result[1][3];
      $cur_vms["resources"] = $result[1][4];
      if (preg_match("/AccessVM/", $result[1][5])) {
        preg_match("/\((.*?)\)/", $cur_vms["host"], $ip);
        $out["accessvm"] = $ip[1];
        continue;
      }

      $out[] = $cur_vms;
    }
    return $out;
  }

}
