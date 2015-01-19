<?php
return array(
    'controllers' => array(
        'invokables' => array(
             'Traveler\Controller\Traveler' => 'Traveler\Controller\TravelerController',
        ),
    ),

    // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'traveler' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/traveler/[:id[/]][:action]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Traveler\Controller\Traveler',
                         'action'     => 'edit',
                     ),
                 ),
             ),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/traveler',
                    'defaults' => array(
                        'controller' => 'Traveler\Controller\Traveler',
                        'action'     => 'index',
                    ),
                ),
            ),
            'me' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/traveler/me',
                    'defaults' => array(
                        'controller' => 'Traveler\Controller\Traveler',
                        'action'     => 'index',
                    ),
                ),
            ),
         ),
     ),


    'view_manager' => array(
        'template_path_stack' => array(
            'traveler' => __DIR__ . '/../view',
        ),
    ),
);


