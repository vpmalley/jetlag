<?php
namespace Traveler\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Traveler\Model\Traveler;
use Traveler\Model\AuthUser;
use Traveler\Model\AuthUserTable;
use Traveler\Form\TravelerForm;
use Traveler\Controller\AuthAdapter;
use Traveler\Controller\SessionRedirectionService;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;

class TravelerController extends AbstractActionController
{
  protected $travelerTable;
  protected $authUserTable;

  /*
   *  endpoint /traveler/index
   */
  public function indexAction()
  {
    $isAuthenticated = $this->isAuthenticated();
    if ($isAuthenticated) {
      $traveler = $this->getAuthenticatedTraveler();
      $result = array(
        'isAuthenticated' => $isAuthenticated,
        'username' => $traveler->username,
        'id' => $traveler->id
      );
    } else {
      $result = array(
        'isAuthenticated' => $isAuthenticated
      );
    }

    return new ViewModel($result);
  }

  /*
   *  endpoint /traveler/all
   */
  public function allAction()
  {
    $this->isAuthenticatedRedirects('all');
      return new ViewModel(array(
      'travelers' =>
      $this->getTravelerTable()->fetchAll()
      ));
  }

  /*
   *  endpoint /traveler/add
   */
  public function addAction()
  {
    $form = new TravelerForm();
    $form->get('submit')->setValue('Create account');
    
    $request = $this->getRequest();
    if ($request->isPost()) {
      $traveler = new Traveler();
      $form->setInputFilter($traveler->getInputFilter());
      $form->setData($request->getPost());
      
      if ($form->isValid()) {
        $traveler->exchangeArray($form->getData());
        $password = (!empty($form->getData()['password'])) ? $form->getData()['password'] : null;
        try {
          $this->getTravelerTable()->saveTraveler($traveler);
        } catch (\InvalidArgumentException $e){
          echo '<b>', $e->getMessage(), '</b>';
        }
        $traveler = $this->getTravelerTable()->getTravelerByEmail($traveler->emailAddress);
        // if saving the traveler was successful and an id has been generated, let's now add the password
        if ($traveler) {
          $this->getAuthUserTable()->saveAuthUser($traveler, $password);
          
          // Redirect to 'traveler' route if everything went fine
          return $this->redirect()->toRoute('traveler', array('id' => $traveler->id));
        }
      }
    }
    return array('form' => $form, 'hasForm' => true);
   }


  /*
   *  endpoint /traveler/{id}/edit
   */
   public function editAction()
   {
    $this->isAuthenticatedRedirects('edit');
    $id = (int) $this->params()->fromRoute('id', 0);
    if (!$id) {
      return $this->redirect()->toRoute('traveler', array(
        'action' => 'add'
      ));
    }

    // Get the Traveler with the specified id.  An exception is thrown
    // if it cannot be found, in which case go to the index page.
    try {
      $traveler = $this->getTravelerTable()->getTraveler($id);
    }
    catch (\Exception $ex) {
      echo("Error. No Traveler found");
      return $this->redirect()->toRoute('traveler', array(
        'action' => 'add'
      ));
    }
    
    $form  = new TravelerForm();
    $form->bind($traveler);
    $form->get('submit')->setAttribute('value', 'Save your changes');

    $request = $this->getRequest();
    if ($request->isPost()) {
      $form->setInputFilter($traveler->getInputFilter(false));
      $form->setData($request->getPost());
    
      if ($form->isValid()) {
        $this->getTravelerTable()->saveTraveler($traveler);
        return $this->redirect()->toRoute('traveler', array('id' => $id ));
      }
    }

    return array(
      'id' => $id,
      'form' => $form
    );
  }

  /*
   *  endpoint /traveler/{id}/delete
   */
  public function deleteAction()
  {
    $redirServ = new SessionRedirectionService();
    if (!$this->getRequest()->isPost()) {
      $redirServ->storeOrigAction($this, 'delete');
    }
    $this->isAuthenticatedRedirects('delete');

    $id = (int) $this->params()->fromRoute('id', 0);
    if (!$id) {
      return $redirServ->redirectToOrigAction($this, 'traveler', 'delete');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $del = $request->getPost('del', 'No');

      if ($del == 'Yes') {
        $id = (int) $request->getPost('id');
        $this->getTravelerTable()->deleteTraveler($id);
        $this->getAuthUserTable()->deleteAuthUser($id);
      }
      return $redirServ->redirectToOrigAction($this, 'traveler', 'delete', $id);
    }
    
    return array(
      'id' => $id,
      'traveler' => $this->getTravelerTable()->getTraveler($id)
    );
  }

  /*
   *  endpoint /traveler/log
   */
  public function logAction()
  {
    $redirServ = new SessionRedirectionService();
    if (!$this->getRequest()->isPost()) {
      $redirServ->storeOrigAction($this, 'log');
    }

    if ($this->isAuthenticated()) {
      $traveler = $this->getAuthenticatedTraveler();
      return $redirServ->redirectToOrigAction($this, 'traveler', $traveler->id);
    } else {
      $emailAddress = $this->params()->fromQuery('email');
      $form = new TravelerForm();
      $form->get('emailAddress')->setValue($emailAddress);
      $form->get('submit')->setValue('Log in');
      
      $request = $this->getRequest();
      if ($request->isPost()) {
        $authUser = new AuthUser();
        $form->setInputFilter($authUser->getInputFilter());
        $form->setData($request->getPost());

        if ($form->isValid()) {
          $emailAddress = (!empty($form->getData()['emailAddress'])) ? $form->getData()['emailAddress'] : null;
          $password = (!empty($form->getData()['password'])) ? $form->getData()['password'] : null;

          if ($this->authenticate($emailAddress, $password)) {
            $traveler = $this->getTravelerTable()->getTravelerByEmail($emailAddress);
            if ($traveler) {
              $id = $traveler->id;
              return $redirServ->redirectToOrigAction($this, 'traveler', 'log', $id);
            } else {
              return $this->redirect()->toRoute('home');
            }
          } else {
            echo '<b>Wrong Credentials</b>';
          }
        }
      }
      return array('form' => $form,
        'hasForm' => true);
    }
  }

  /*
   *  endpoint /traveler/logout
   */
  public function logoutAction(){
    return $this->clearAuthentication();
  }

  // Tables

  private function getTravelerTable()
  {
    if (!$this->travelerTable) {
      $sm = $this->getServiceLocator();
      $this->travelerTable = $sm->get('Traveler\Model\TravelerTable');
    }
    return $this->travelerTable;
  }

  private function getAuthUserTable()
  {
    if (!$this->authUserTable) {
      $sm = $this->getServiceLocator();
      $this->authUserTable = $sm->get('Traveler\Model\AuthUserTable');
    }
    return $this->authUserTable;
  }

   // Authentication

  public function authenticate($emailAddress, $password = "")
  {
    $authAdapter = new AuthAdapter($this->getAuthUserTable(), $emailAddress, $password);

    $auth = new AuthenticationService();
    $result = $auth->authenticate($authAdapter);
    return $result->isValid();
  }

  /**
   * If a user is authenticated, returns nothing.
   * If a user is not authenticated, redirects to the login page. Once logged in, redirects to that page.
   * @param origAction the calling action
   * @return nothing
   */
  public function isAuthenticatedRedirects($origAction)
  {
    if (!$this->isAuthenticated()) {
      return $this->redirect()->toRoute('traveler', array('action' => 'log'), array('query' => array('origAction' => $origAction)));
    }
  }

  /**
   * Whether a user is authenticated
   * @return boolean
   */
  public function isAuthenticated()
  {
    $auth = new AuthenticationService();
    return $auth->hasIdentity();
  }

  /**
   * If a user is authenticated, returns the associated traveler
   * @return Traveler
   * @pre it is assumed the traveler is authenticated, which is checked with isAuthenticated()
   */
  public function getAuthenticatedTraveler()
  {
    $traveler = null;
    $auth = new AuthenticationService();
    if ($auth->hasIdentity()) {
      $identity = $auth->getIdentity();
      $traveler = $this->getTravelerTable()->getTravelerByEmail($identity);
    }
    return $traveler;
  }

  /**
   * If a user is authenticated, un-authenticate him/her
   * @return nothing
   */
  public function clearAuthentication()
  {
    $auth = new AuthenticationService();
    if ($auth->hasIdentity()) {
      $auth->clearIdentity();
      // apparently that is not the right way to clear the identity in session
    }
    return $this->redirect()->toRoute('traveler', array('action' => 'index'));
  }

}


