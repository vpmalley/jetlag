<?php
namespace Traveler\Model;

use Zend\Db\TableGateway\TableGateway;

class TravelerTable
{
  protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getTraveler($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function getTravelerByEmail($emailAddress)
     {
         $emailAddress  = (string) $emailAddress;
         $rowset = $this->tableGateway->select(array('emailAddress' => $emailAddress));
         $row = $rowset->current();
         return $row;
     }

  public function saveTraveler(Traveler $traveler)
  {
    $data = array(
      'username' => $traveler->username,
      'emailAddress'  => $traveler->emailAddress,
    );

    $id = (int) $traveler->id;
    if ($id == 0) {
      if (!$this->getTravelerByEmail($traveler->emailAddress)) {
        $id = $this->tableGateway->insert($data);
      } else {
        throw new \InvalidArgumentException('A traveler with this email address already exists');
      }
    } else {
      if ($this->getTraveler($id)) {
        error_log($data['username']);
        $this->tableGateway->update($data, array('id' => $id));
      } else {
        throw new \InvalidArgumentException('Traveler id does not exist');
      }
    }
    return $id;
  }

     public function deleteTraveler($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }

 }
