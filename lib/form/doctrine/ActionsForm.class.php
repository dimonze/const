<?php

/**
 * Actions form.
 *
 * @package    const
 * @subpackage form
 * @author     dimonze
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ActionsForm extends BaseActionsForm
{
  public function configure()
  {
    $this->setWidget('sample_act', new sfWidgetFormTextarea(array(), array(
                'cols' => 200,
                'rows' => 20)));
    $this->setWidget('description', new sfWidgetFormTextarea(array(), array(
                'cols' => 50,
                'rows' => 10)));
    
    $this->setValidator('sample_act', new sfValidatorString(array(
      'required' => false,
      'empty_value' => null
    )));
    $this->setValidator('description', new sfValidatorString(array(
      'required' => false,
      'empty_value' => null
    )));
  }
}
