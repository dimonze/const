<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version21 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('current_state', 'outputpath', 'string', '100', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('current_state', 'outputpath');
    }
}