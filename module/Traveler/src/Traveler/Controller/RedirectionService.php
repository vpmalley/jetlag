<?php
namespace Traveler\Controller;

interface RedirectionService {
  
   /**
    * Stores the original action, to be retrieved for redirection later
    * @param currentAction the action for which we store the previous action
    * @return nothing
    */
   public function storeOrigAction($currentAction);
   
   /**
    * Stores the original action, to be retrieved for redirection later
    * @param currentAction the action from which we go to the previous action
    * @param id the id for this traveler (optional)
    * @return the redirection
    * @pre the original action must have been stored with storeOrigAction()
    */
   public function redirectToOrigAction($currentAction, $id = null);
  
}
