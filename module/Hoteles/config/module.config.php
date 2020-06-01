<?php


return array(
    
    'view_manager' => array(
    		'template_path_stack' => array(
    				'Hoteles' => __DIR__ . '/../view',
    		),
    ),
		
	'controllers' => array(
		'invokables' => array(
			'hoteles-index-controller'    => 'Hoteles\Controller\IndexController',
			//'hoteles-estado-controller'   => 'Hoteles\Controller\EstadoController',
			'hoteles-vista-controller'    => 'Hoteles\Controller\VistaController',
		    'hoteles-hotel-controller'    => 'Hoteles\Controller\HotelController',
			
		
		)
    ),
		
	'router' => array(
    	'routes' => array(
    		'home' => array(
    			'type'    => 'Literal',
    			'options' => array(
    				'route'    => '/',
    				'defaults' => array(
    						'controller' => 'hoteles-index-controller',
    						'action'     => 'index',
    				
    				)
    			),
    		),
//     	    'hoteles-estado' => array(
//     	        'type' => 'hoteles-estado-route',
//     	        'options' => array(
//     	            'route' => '/hotelesen(/\S+)?',
//     	            'defaults' => array(
//     	                'controller' => 'hoteles-estado-controller',
//     	                'action'     => 'index'
//     	            )
    	             
//     	        ),
//     	    ),
    	    'hoteles-vista' => array(
    	        'type' => 'hoteles-vista-route',
    	        'options' => array(
    	            'route' => '/hotels/(\S+)?',
    	            'defaults' => array(
    	        								'controller' => 'hoteles-vista-controller',
    	        								'action'     => 'index'
    	            )
    	    
    	        ),
    	    ),
    	    
    	    
    	    'hotel' => array(
    	        'type'    => 'segment',
    	        'options' => array(
    	            'route'      => '/hotel[/:id][-:title]',
    	            'constrains' => array(
    	                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id'     => '[0-9]+'
    	            ),
    	            'defaults' => array(
    	                'controller' => 'hoteles-hotel-controller',
    	                'action'     => 'info'
    	            )

    	        ),
    	    ),
    	    
		)
    ),
		
	'service_manager' => array(
	    
    	'factories' => array(
			//'hoteles-estados-navigation'       => 'Hoteles\Factory\EstadosNavigationFactory',
    		//'hoteles-estado-vistas-navigation' => 'Hoteles\Factory\EstadoVistasNavigationFactory',
    	    'cache-service' => function() {
    	        return \Zend\Cache\StorageFactory::factory(array(
    	            'adapter' => array(
    	                'name' => 'filesystem',
    	                'options' => array(
    	                    'cache_dir' => 'data/cache/',
    	                    'ttl' => 20
    	                ),
    	            ),
    	        ));
    	    },
		),
		'invokables' => array(
				'hoteles-foto-service'      => 'Hoteles\Service\FotoService',
				'hoteles-hotel-service'     => 'Hoteles\Service\HotelService',
		)	
    ),
		
	'navigation' => array(
    	'default' => array(
			array(
					'label' => 'Playa del Carmen',
			         'uri' => '/hotels/Playa+del+Carmen'
			),
    	    array(
    	        'label' => 'Mayan Riviera',
    	        'uri' => '/hotels/Mayan+Riviera'
    	    ),
    	    array(
    	        'label' => 'Cozumel',
    	        'uri' => '/hotels/Cozumel'
    	    ),
    	    array(
    	        'label' => 'Tulum',
    	        'uri' => '/hotels/Tulum'
    	    ),
    	    array(
    	        'label' => 'Holbox',
    	        'uri' => '/hotels/Holbox+Island'
    	    ),
    	    array(
    	        'label' => 'Cancun',
    	        'uri' => '/hotels/Cancun'
    	    ),
    	    
    	    array(
    	        'label' => 'More Destinations',
    	        'uri' => '#',
    	        'pages' => array(
    	            array(
    	                'label' => 'Isla Mujeres',
    	                'uri' => '/hotels/Isla+Mujeres'
    	            ),
    	            array(
    	                'label' => 'Chetumal',
    	                'uri' => '/hotels/Chetumal'
    	            ),
    	            array(
    	                'label' => 'Bacalar',
    	                'uri' => '/hotels/Bacalar'
    	            ),
    	            array(
    	                'label' => 'Other Mexican Desinations',
    	                'class' => 'divider',
    	                'uri' => '#'
    	            ),
    	            array(
    	                'label' => 'Acapulco',
    	                'uri' => 'https://www.turista.com.mx/acapulco-hotels-1.html'
    	            ),
    	            array(
    	                'label' => 'Los Cabos',
    	                'uri' => 'https://www.turista.com.mx/los+cabos-hotels-219.html'
    	            ),
    	            array(
    	                'label' => 'Mazatlan',
    	                'uri' => 'https://www.turista.com.mx/mazatlan-hotels-5.html'
    	            ),
    	            array(
    	                'label' => 'Puerto Vallarta',
    	                'uri' => 'https://www.turista.com.mx/puerto+vallarta-hotels-8.html'
    	            ),
    	            array(
    	                'label' => 'Huatulco',
    	                'uri' => 'https://www.turista.com.mx/huatulco-hotels-15.html'
    	            ),
    	        ),
    	    ),
    	        
    	    
		),
				
			
    )
    
);

