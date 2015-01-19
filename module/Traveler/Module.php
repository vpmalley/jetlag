<?php
 namespace Traveler;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;
 use Traveler\Model\Traveler;
 use Traveler\Model\TravelerTable;
 use Traveler\Model\AuthUser;
 use Traveler\Model\AuthUserTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;


 class Module implements AutoloaderProviderInterface, ConfigProviderInterface
 {
     public function getAutoloaderConfig()
     {
         return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
     }

     public function getConfig() {
         return include __DIR__ . '/config/module.config.php';
     }
     
     public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Traveler\Model\TravelerTable' =>  function($sm) {
                     $tableGateway = $sm->get('TravelerTableGateway');
                     $table = new TravelerTable($tableGateway);
                     return $table;
                 },
                 'TravelerTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Traveler());
                     return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                 },
                 'Traveler\Model\AuthUserTable' =>  function($sm) {
                     $tableGateway = $sm->get('AuthUserTableGateway');
                     $table = new AuthUserTable($tableGateway);
                     return $table;
                 },
                 'AuthUserTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new AuthUser());
                     return new TableGateway('authUsers', $dbAdapter, null, $resultSetPrototype);
                 },
             ),
         );
     }

 }
