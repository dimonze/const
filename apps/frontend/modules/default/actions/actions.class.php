<?php


class defaultActions extends sfActions
{
  public function executeLogin(sfWebRequest $request)
  {   
    $this->_users = Doctrine::getTable('Users')->findAll();    
    $auth = array();    
    foreach ($this->_users as $value)
    {
      $auth[$value->user] = array(
        'pass' => $value->password, 'credentials' => array('admin'), 'suptype' => null);
    }
    
    $this->_failed = false;
    $this->form = new sfForm();
    $this->form->setWidgets(array(
      'login'     => new sfWidgetFormInput(),
      'pass'      => new sfWidgetFormInputPassword()
    ));
    $this->form->getWidgetSchema()->setNameFormat('auth[%s]');

    if ($request->isMethod('post') && $request->hasParameter('auth')) {
      $values = $request->getParameter('auth');
      if (!in_array($values['login'], array_keys($auth))) {
        $this->getUser()->setFlash('error', 'Неверный логин или пароль');
        return;
      }

      $this->form->setValidators(array(
        'login'     => new sfValidatorChoice(array('required' => true, 'choices' => array($values['login']))),
        'pass'      => new sfValidatorChoice(array('required' => true, 'choices' => array($auth[$values['login']]['pass'])))
      ));

      $this->form->bind($values);
      if ($this->form->isValid()) {
        $this->getUser()->setAuthenticated(true);
        $this->getUser()->addCredentials($auth[$values['login']]['credentials']);
        $this->getUser()->setAttribute('suptype', $auth[$values['login']]['suptype']);
        $this->getUser()->setAttribute('username', $values['login']);
        if ($request->getReferer() != $this->getController()->genUrl('default/login', true)) {
          $this->redirect($request->getReferer());
        }
        else {
          $this->redirect('@homepage');
        }
      } else {
        $this->_failed = true;
      }
    }
  }

  public function executeLogout()
  {
    $this->getUser()->clearCredentials();
    $this->getUser()->setAuthenticated(false);
    $this->getUser()->getAttributeHolder()->clear();

    $this->getController()->redirect('default/login');
  }


  public function executeSecure()
  {
    $this->getResponse()->setStatusCode('403');
  }
 
}
