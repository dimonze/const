<?php

/**
 * parameters actions.
 *
 * @package    const
 * @subpackage parameters
 * @author     dimonze
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class parametersActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($request->getParameter('params_type'));
    if ($request->getParameter('generalNeeded')) {
      $this->parameterss = Doctrine::getTable('parameters')->getRequiredParameters($request->getParameter('params_type'));
    } else {
      $this->parameterss = Doctrine::getTable('parameters')->getRequiredParametersWOGeneral($request->getParameter('params_type'));
    }
  }
}
