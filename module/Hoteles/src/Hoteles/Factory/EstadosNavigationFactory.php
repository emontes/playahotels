<?php
namespace Hoteles\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Hoteles\Navigation\EstadosNavigation;

class EstadosNavigationFactory implements FactoryInterface
{

	public function createService(ServiceLocatorInterface $serviceLocator) 
	{
		$navigation = new EstadosNavigation();
		return $navigation->createService($serviceLocator);

	}
	
}