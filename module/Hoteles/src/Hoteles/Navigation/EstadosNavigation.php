<?php
namespace Hoteles\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

class EstadosNavigation extends DefaultNavigationFactory
{
	protected function getPages(ServiceLocatorInterface $serviceLocator) 
	{
		if (null === $this->pages) {
			
			
		}
		
		
		$application = $serviceLocator->get('Application');
		$routeMatch  = $application->getMvcEvent()->getRouteMatch();
		$router      = $application->getMvcEvent()->getRouter();
		$estadoId = $routeMatch->getParam('estadoid');
		
		$em = $serviceLocator->get('Doctrine\ORM\EntityManager');
		$config = $serviceLocator->get('turista-config');
		
		$configuration['navigation'][$this->getName()] = array();
		$estados = $em->getRepository('Hoteles\Entity\\'. $config['queHoteles'] . 'Estados')->findAll();
		foreach ($estados as $estado) {
			if ($estado->getId() == $estadoId) {
				$active = true;
			} else {
				$active = false;
			}
			$configuration['navigation'][$this->getName()][$estado->getId()] = array(
				'label' => $estado->getNombre(),
				'id'     => 'estado' . $estado->getId(),
				'uri'    => '/hotelesen/' . urlencode($estado->getNombre()),
				'active' => $active,
			);
		}
 
            
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
 
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
		return $this->pages;

	}
	
	



}
