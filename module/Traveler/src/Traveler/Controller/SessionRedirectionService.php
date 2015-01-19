<?php
namespace Traveler\Controller;

use Zend\Authentication\Storage\Session as SessionStorage;

class SessionRedirectionService {
  
   /**
    * Stores the original action, to be retrieved for redirection later
    * @param currentAction the action for which we store the previous action
    * @return nothing
    */
   public function storeOrigAction($controller, $currentAction) {
     $origAction = $controller->params()->fromQuery('origAction');
     $session = new SessionStorage('origAction' . $currentAction);
     $session->write($origAction);
   }
   
   /**
    * Stores the original action, to be retrieved for redirection later
    * @param route the name of the route to use
    * @param currentAction the action from which we go to the previous action
    * @param id the id for this traveler (optional)
    * @return the redirection
    * @pre the original action must have been stored with storeOrigAction()
    */
   public function redirectToOrigAction($controller, $route, $currentAction, $id = null){
     $session = new SessionStorage('origAction' . $currentAction);
     $origAction = $session->read();
     if (is_null($id)) {
       $params = array('action' => $origAction);
     } else {
       $params = array('id' => $id, 'action' => $origAction);
     }
     $session->clear();
     return $controller->redirect()->toRoute($route, $params);
   }
  
}
