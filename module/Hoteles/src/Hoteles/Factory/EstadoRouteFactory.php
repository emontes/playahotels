<?php
namespace Hoteles\Factory;

use Hoteles\Route\EstadoRoute;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\Authentication\Adapter\DbTable\Exception\RuntimeException;

class EstadoRouteFactory implements FactoryInterface, MutableCreationOptionsInterface
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
		//$em = $sm->get('Doctrine\ORM\EntityManager');
		$config = $sm->get('turista-config');
		//$estadoRepo = $em->getRepository('Hoteles\Entity\\' . $config['queHoteles'] . 'Estados');
		return new EstadoRoute($this->creationOptions, $estadoRepo);

	}





}
