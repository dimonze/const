<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version6 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('parameters', 'params_value', 'string', '100', array(
             ));
    }

    public function down()
    {

    }
}