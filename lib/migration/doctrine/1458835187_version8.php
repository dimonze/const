<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version8 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('actions', 'action_type', 'string', '100', array(
             'notnull' => '',
             ));
    }

    public function down()
    {

    }
}