<?php
  namespace Traveler\Controller;
 
  use Zend\Authentication\Adapter\AdapterInterface;
  use Zend\Authentication\Result;
  
  class AuthAdapter implements AdapterInterface
  {
    
    private $authIdentity;
    private $authCredential;
    
    private $authUserTable;
    
    public function __construct($authUserTable, $username = null, $password = null)
    {
      $this->authIdentity = $username;
      $this->authCredential = $password;
      $this->authUserTable = $authUserTable;
    }
    
    public function authenticate()
    {
      $result;
      if ((!is_null($this->authUserTable)) && (!is_null($this->authIdentity)) && (!is_null($this->authCredential))){
        $authenticated = $this->authUserTable->verifyAuthUser($this->authIdentity, $this->authCredential);
      }
      if ($authenticated) {
        $result = new Result(Result::SUCCESS, $this->authIdentity, array());
      } else {
        $result = new Result(Result::FAILURE, $this->authIdentity, array("Wrong username or password."));
      }
      return $result;
    }
  }
