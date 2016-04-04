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
  private $foo = FALSE;

    public function __construct()
    {
        $this->foo = new Net_SSH2('localhost');
        
        echo($this->foo);
    }
}
