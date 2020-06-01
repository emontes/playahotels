<?php
namespace Hoteles\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HotelService implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	
	
	
	private function getEntityManager()
	{
		$em = $this->getServiceLocator()
		->get('Doctrine\ORM\EntityManager');
		return $em;
	}
	
	public function getHotelRooms($hotel)
	{
		$em = $this->getEntityManager();
		$config = $this->serviceLocator->get('turista-config');
		$roomsEntity = 'Hoteles\Entity\\' . $config['queHoteles'] . 'Rooms';
		$rooms = $em->getRepository($roomsEntity)->findBy((array(
				'hotelid' => $hotel->getHotelid(),
		)));
		return $rooms;
	}
	
	public function getHotelRoomMealPlans($roomid)
	{
		$em = $this->getEntityManager();
		$config = $this->serviceLocator->get('turista-config');
		$mealPlansEntity = 'Hoteles\Entity\\' . $config['queHoteles'] . 'RoomsMealPlans';
		$mealPlans = $em->getRepository($mealPlansEntity)->findBy((array(
			'roomid' => $roomid
		)));
		return $mealPlans;
	}
	
}
