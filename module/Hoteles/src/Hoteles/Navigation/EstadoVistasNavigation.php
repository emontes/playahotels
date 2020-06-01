<?php
namespace Hoteles\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

class EstadoVistasNavigation extends DefaultNavigationFactory
{
	protected function getPages(ServiceLocatorInterface $serviceLocator) 
	{
		if (null === $this->pages) {
			
		}
		$application = $serviceLocator->get('Application');
		$routeMatch  = $application->getMvcEvent()->getRouteMatch();
		$router      = $application->getMvcEvent()->getRouter();
		$estadoId = $routeMatch->getParam('estadoid');
		$vistaId = $routeMatch->getParam('vistaid');
		$estadoNombre = $routeMatch->getParam('nombre');
		
		
		$em = $serviceLocator->get('Doctrine\ORM\EntityManager');
		$config = $serviceLocator->get('turista-config');
		$configuration['navigation'][$this->getName()] = array();
		$vistas = $em->getRepository('Hoteles\Entity\\'. $config['queHoteles'] . 'Hotelesviews')
		->findBy(array('estado' => $estadoId, 'parentid'=> '0'));
		foreach ($vistas as $vista) {
			if ($vista->getHviid() == $vistaId) {
				$active = true;
			} else {
				$active = false;
			}
			$configuration['navigation'][$this->getName()][$vista->getHviid()] = array(
				'label' => $vista->getHviDescEngish(),
				'uri'   => '/hotels/' . urlencode($vista->getHviDescEnglish()),
				'active' => $active,
			);
		}
 
            
			$pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
		return $this->pages;

	}
	
	



}
