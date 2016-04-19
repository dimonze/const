<?php

/**
 * Current_stateTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Current_stateTable extends Doctrine_Table
{

  /**
   * Returns an instance of this class.
   *
   * @return object Current_stateTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('Current_state');
  }

  public function getInprogressTasks($user)
  {
    return $this->createQuery('us')
                    ->where(('us.owner LIKE ?'), $user)
                    ->andWhere('us.state LIKE "in progress"')
                    ->execute();
  }

  public function getAllTasks($user)
  {
    return $this->createQuery('us')
                    ->where(('us.owner LIKE ?'), $user)
                    ->orderBy('FIELD(state, "in progress") desc, ID desc')
                    ->limit('20')
                    ->execute();
  }
  
    public function getAvgTime()
  {
    return $this->createQuery('us')
                    ->select('action_name, AVG(execution_time)')
                    ->where('us.execution_time IS NOT NULL')
                    ->groupBy('action_name')
                    ->execute();
  }

}
