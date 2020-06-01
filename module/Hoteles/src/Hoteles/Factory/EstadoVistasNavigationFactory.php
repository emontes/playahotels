<?php
namespace Hoteles\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Hoteles\Navigation\EstadoVistasNavigation;

class EstadoVistasNavigationFactory implements FactoryInterface
{

	public function createService(ServiceLocatorInterface $serviceLocator) 
	{
		$navigation = new EstadoVistasNavigation();
		return $navigation->createService($serviceLocator);

	}
	
}