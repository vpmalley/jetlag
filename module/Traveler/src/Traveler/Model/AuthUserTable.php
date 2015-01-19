<?php
 namespace Traveler\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Crypt\Password\Bcrypt;

 class AuthUserTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }
     
     private function getAuthUser($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         return $row;
     }

     private function getAuthUserByEmail($emailAddress)
     {
         $emailAddress  = (string) $emailAddress;
         $rowset = $this->tableGateway->select(array('emailAddress' => $emailAddress));
         $row = $rowset->current();
         return $row;
     }
     
     /**
      * Verifies if a user is registered and verifies the password matches the stored password.
      * This returns simply a boolean, whether the user is recognized and authenticated or not.
      * This means that it does not distinguish the reason why the user is not recognized (wrong user, wrong password).
      * 
      */
     public function verifyAuthUser($emailAddress, $password)
     {
       $authenticated  = false;
       
       $authUser = $this->getAuthUserByEmail($emailAddress);
       if ($authUser) {
        $hash = $authUser->password;
        $authenticated = password_verify($password, $hash);
       }  
       return $authenticated;
     }
     
     public function saveAuthUser(Traveler $traveler, $password) {
       $bcrypt = new Bcrypt();
         $data = array(
             'id'  => $traveler->id,
             'emailAddress'  => $traveler->emailAddress,
             'password'  => password_hash($password, PASSWORD_BCRYPT)
         );

			if ($this->getAuthUser($traveler->id)){
				$this->tableGateway->update($data, array('id' => $traveler->id));
			} else {
				$this->tableGateway->insert($data);
			}
	 }
	 
     public function deleteAuthUser($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
     
 }
