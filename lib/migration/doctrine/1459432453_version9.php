<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version9 extends Doctrine_Migration_Base
{
    public function up()
    {        
        $this->addColumn('actions', 'without_general_params', 'boolean', '25', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {       
        $this->removeColumn('actions', 'without_general_params');
    }
}