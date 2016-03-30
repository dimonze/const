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
    $this->parameterss = Doctrine_Core::getTable('Parameters')->findBy('params_type', $request->getParameter('params_type'));
    $this->paramArray = array();
    foreach ($this->parameterss as $value)
    {
      $this->paramArray["Param" . $value->getId()]['ParamsName'] = $value->getParamsName();
      $this->paramArray["Param" . $value->getId()]['ParamsValue'] = $value->getParamsValue();
      $this->paramArray["Param" . $value->getId()]['ParamsType'] = $value->getParamsType();
      $this->paramArray["Param" . $value->getId()]['Optional'] = $value->getOptional();
      $this->paramArray["Param" . $value->getId()]['Description'] = $value->getDescription();
      $this->paramArray["Param" . $value->getId()]['Owner'] = $value->getOwner();
    }    
    $this->paramArray = json_encode($this->paramArray);
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->parameters = Doctrine_Core::getTable('Parameters')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->parameters);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ParametersForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ParametersForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($parameters = Doctrine_Core::getTable('Parameters')->find(array($request->getParameter('id'))), sprintf('Object parameters does not exist (%s).', $request->getParameter('id')));
    $this->form = new ParametersForm($parameters);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($parameters = Doctrine_Core::getTable('Parameters')->find(array($request->getParameter('id'))), sprintf('Object parameters does not exist (%s).', $request->getParameter('id')));
    $this->form = new ParametersForm($parameters);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($parameters = Doctrine_Core::getTable('Parameters')->find(array($request->getParameter('id'))), sprintf('Object parameters does not exist (%s).', $request->getParameter('id')));
    $parameters->delete();

    $this->redirect('parameters/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $parameters = $form->save();

      $this->redirect('parameters/edit?id='.$parameters->getId());
    }
  }
}
