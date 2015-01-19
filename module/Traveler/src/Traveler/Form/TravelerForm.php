<?php
 namespace Traveler\Form;

 use Zend\Form\Form;

 class TravelerForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('traveler');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'username',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Pseudo',
             ),
         ));
         $this->add(array(
             'name' => 'emailAddress',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Email',
             ),
         ));
         $this->add(array(
             'name' => 'password',
             'type' => 'Password',
             'options' => array(
                 'label' => 'Password',
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }
