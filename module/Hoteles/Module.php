<?php
/**
 * Turistamexico (http://www.turistamexico.com)
 * Module: Hoteles
 */
namespace Hoteles;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response;

class Module
{
    private function getCacheKey($params)
    {
        $key = 'c' . $params['controller'] . 'a' . $params['action'];
        if (isset($params['vistaid'])) {
            $key .= 'v' . $params['vistaid'];
        }
        if (isset($params['page'])) {
            $key .= 'p' . $params['page'];
        }
        if (isset($params['id'])) {
            $key .= 'id' . $params['id'];
        }
        return $key;
    }
    
	public function onBootStrap(MvcEvent $e)
	{
	    $routes = array('home', 'hoteles-vista');
	    
		$em = $e->getApplication()->getEventManager();
		$serviceManager = $e->getApplication()->getServiceManager();
		
		
		
		
		$em->attach(
		    MvcEvent::EVENT_ROUTE,
		    function ($e) use ($serviceManager, $routes) {
		        $route = $e->getRouteMatch()->getMatchedRouteName();
		        $params = $e->getRouteMatch()->getParams();
		        $key = $this->getCacheKey($params);

		        if (!in_array($route, $routes)) {
		            return;
		        }
		        $cache = $serviceManager->get('cache-service');
		        //$key = 'route-'.$route;
		        if ($cache->hasItem($key)) {
		            $response = $e->getResponse();
		            $response->setContent($cache->getItem($key));
		            return $response;
		        }
		    }, 
		    -10000);
		
		$em->attach(
		    MvcEvent::EVENT_RENDER,
		    function ($e) use ($serviceManager, $routes) {
		        $route = $e->getRouteMatch()->getMatchedRouteName();
		        if (!in_array($route, $routes)) {
		            return ;
		        }
		        $response = $e->getResponse();
		        $cache = $serviceManager->get('cache-service');
		        $params = $e->getRouteMatch()->getParams();
		        $key = $this->getCacheKey($params);
		        //$key = 'route-'.$route;
		       
		        $cache->setItem($key, $response->getContent());
		    },
		    -10000);
	}
	
	
    public function getConfig()
    {
    	return include __DIR__ . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
    	return array(
    			'Zend\Loader\StandardAutoloader' => array(
    					'namespaces' => array(
    							__NAMESPACE__ => __DIR__ . '/src/' .
    							__NAMESPACE__,
    					),
    			),
    	);
    }
    
    public function getRouteConfig()
    {
    	return array(
    			'factories' => array(
    					//'hoteles-estado-route' => 'Hoteles\Factory\EstadoRouteFactory',
    					'hoteles-vista-route'  => 'Hoteles\Factory\VistaRouteFactory',
    			),
    			
    	);
    }
    
}

