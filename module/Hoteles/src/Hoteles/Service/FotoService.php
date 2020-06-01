<?php
namespace Hoteles\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class FotoService implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}
	
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	
	public function getEntityManager()
	{
		$em = $this->getServiceLocator()
		->get('Doctrine\ORM\EntityManager');
		return $em;
	}
	
	public function getFotosEntity()
	{
		$em = $this->getEntityManager();
		$config = $this->serviceLocator->get('turista-config');
		$fotosEntity = 'Hoteles\Entity\\' . $config['queHoteles'] . 'Fotos';
		return $fotosEntity;
	}
	
	public function updateNombreHotel($hotel, $viejo, $nuevo, $imgPortada)
	{
		$em = $this->getEntityManager();
		$config = $this->serviceLocator->get('turista-config');
		$viejoSano = strtolower($this->sanear_string($viejo));
		$nuevoSano = strtolower($this->sanear_string($nuevo));
		$vistaDesc = $em->getRepository( 'Hoteles\Entity\\' . $config['queHoteles'] . 'Hotelesviews' )
		->getVistaDescription ( $hotel->getVista() );
		$directorioVista = $this->getDirectorioVista($hotel->getVista(),$vistaDesc,null,'dir');
		if (is_dir($directorioVista . $viejoSano)) {
			rename($directorioVista . $viejoSano, $directorioVista . $nuevoSano);
			echo "Se renombro " . $directorioVista.$viejoSano . "\n";
			echo "a " . $directorioVista.$nuevoSano . "\n";
		}
		$this-> deleteHotelFotos($hotel, $directorioVista);
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	public function getPortada($hotel, $tipo='portada_t')
	{
		$em = $this->getEntityManager();
		$config = $this->serviceLocator->get('turista-config');
		$hotelNombre = $hotel->getNombre();
		$directorio = strtolower($this->sanear_string( $hotelNombre ));
		
		$portada = $em->getRepository ('Hoteles\Entity\\' . $config['queHoteles'] . 'Fotos')
		->findOneBy(array('hotelid' => $hotel->getHotelid(), 'tipo' => $tipo ));
		if (!$portada) {
			$portada = $em->getRepository ('Hoteles\Entity\\' . $config['queHoteles'] . 'Fotos')
			->findOneBy(array('hotelid' => $hotel->getHotelid(), 'tipo' => 'portada' ));
		}
		
		if ($portada) {
			$fileName = $portada->getFilename();
			$portadaDescrip = $portada->getDescrip('spanish');
			return $directorio . '/' . $fileName;
		} else {
			return false;
		}
		
		
	}

	public function getDirectorioVista($vistaId, $vistaDesc=null, $estadoDesc=null,$modo='url')
	{
		$config = $this->serviceLocator->get('turista-config');
		$queHoteles = $config['queHoteles'];
		$urlFotos = $config[$modo . 'FotosHoteles'];
		$em = $this->getEntityManager();
		
		if (!isset($vistaDesc)) {
			$vistaDesc = $em->getRepository( 'Hoteles\Entity\\' . $config['queHoteles'] . 'Hotelesviews' )
			->getVistaDescription ( $vistaId, 'spanish' );
		}
		
		$vistaDesc = strtolower($this->sanear_string($vistaDesc));

		if (!isset($estadoDesc)) {
			$vista = $em->getRepository('Hoteles\Entity\\' . $queHoteles . 'Hotelesviews')->find($vistaId);
			$estadoDesc = $vista->getVestado()->getNombre();
			$estadoDesc = strtolower($this->sanear_string($estadoDesc));
		}
		$directorioVista = $urlFotos . $estadoDesc . '/' . $vistaDesc . '/';
		return $directorioVista;
	}
	
	public function sanear_string($string)
	{
	
		$string = trim($string);
	
		$string = str_replace(
				array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
				array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
				$string
		);
	
		$string = str_replace(
				array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
				array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
				$string
		);
	
		$string = str_replace(
				array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
				array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
				$string
		);
	
		$string = str_replace(
				array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
				array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
				$string
		);
	
		$string = str_replace(
				array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
				array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
				$string
		);
	
		$string = str_replace(
				array('ñ', 'Ñ', 'ç', 'Ç'),
				array('n', 'N', 'c', 'C',),
				$string
		);
		
		//Convierte a Texto Especiales
		$string = str_replace(
				array('@','&'), 
				array('at','and'), 
				$string
		);
	
		//Esta parte se encarga de eliminar cualquier caracter extraño
		$string = str_replace(
				array("\\", "¨", "º", "-", "~",
						"#", "@", "|", "!", "\"",
						"·", "$", "%", "&",
						// "/",
						"(", ")", "?", "'", "¡",
						"¿", "[", "^", "`", "]",
						"+", "}", "{", "¨", "´",
						">", "< ", ";", ",", ":",
						".", " "),
				'-',
				$string
		);
	
	
		return $string;
	}
}