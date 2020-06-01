<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'turista-index-controller' => 'Turista\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
//             'home' => array(
//                 'type' => 'Literal',
//                 'options' => array(
//                     'route' => '/',
//                     'defaults' => array(
//                         'controller' => 'turista-index-controller',
//                         'action' => 'index'
//                     )
//                 ),
//             ),
            'turista' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/index',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'turista-index-controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Turista' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'turista-mail-transport' => 'Turista\Factory\TuristaMailTransportFactory',
        	'turista-config'         => 'Turista\Factory\TuristaSiteConfigFactory',
        ),
    ),
);
