<?php
namespace Hoteles\Factory;

use Hoteles\Route\VistaRoute;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\Authentication\Adapter\DbTable\Exception\RuntimeException;

class VistaRouteFactory implements FactoryInterface, MutableCreationOptionsInterface
{
	protected $creationOptions;
	
	public function setCreationOptions(array $creationOptions) 
	{
		if (!isset($creationOptions['route'])) {
			throw new RuntimeException('No se especificó opcion para EstadoRoute');
		}
		
		if (!isset($creationOptions['defaults'])) {
			throw new RuntimeException('No se especificaron defaults para EstadoRoute');
		}
		$this->creationOptions = $creationOptions;

	}
	
	public function createService(ServiceLocatorInterface $serviceLocator) 
	{
		$sm = $serviceLocator->getServiceLocator();
		$config = $sm->get('turista-config');
		$serCache = new \Turista\Service\CacheService();
		$cache = $serCache->getCache('hoteles', '43200'); // 12 horas de cache;
		$apiService = new \Turista\Service\ApiService($config, $cache);
		return new VistaRoute($this->creationOptions, $apiService);

	}





}
