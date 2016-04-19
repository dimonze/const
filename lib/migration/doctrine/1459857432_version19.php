<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version19 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->removeColumn('current_state', 'session_name');
        $this->removeColumn('current_state', 'description');
        $this->addColumn('current_state', 'vm_name', 'string', '100', array(
             'notnull' => '1',
             ));
        $this->addColumn('current_state', 'action_name', 'string', '100', array(
             'notnull' => '1',
             ));
        $this->addColumn('current_state', 'output', 'text', '5000', array(
             ));
        $this->addColumn('current_state', 'state', 'string', '100', array(
             'notnull' => '1',
             ));
        $this->addColumn('current_state', 'execution_time', 'string', '100', array(
             ));
        $this->addColumn('current_state', 'clenup_comand', 'string', '100', array(
             ));
    }

    public function down()
    {
        $this->addColumn('current_state', 'session_name', 'string', '100', array(
             'notnull' => '1',
             ));
        $this->addColumn('current_state', 'description', 'text', '', array(
             ));
        $this->removeColumn('current_state', 'vm_name');
        $this->removeColumn('current_state', 'action_name');
        $this->removeColumn('current_state', 'output');
        $this->removeColumn('current_state', 'state');
        $this->removeColumn('current_state', 'execution_time');
        $this->removeColumn('current_state', 'clenup_comand');
    }
}