<?php

/**
 * Vms
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    const
 * @subpackage model
 * @author     dimonze
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Vms extends BaseVms
{
  public function getShortVMDescr()
  {
    return preg_replace("/".$this->getAccessVmAlias()."-/", "", $this->getDescription());
  }

}