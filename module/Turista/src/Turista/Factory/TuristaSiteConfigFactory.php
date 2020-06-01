<?php
namespace Turista\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TuristaSiteConfigFactory implements FactoryInterface
{

	public function createService(ServiceLocatorInterface $serviceLocator) {
     
        
        	$siteConfig = array(
	        		'siteName'        => 'Playa Hotels',
            	    'nombreEstado'    => 'Quintana Roo',
            	    'nombreEstadoUrl' => 'quintanaroo',
	        		'temaRuta'        => 'http://turista.me/themes/karma1.2',
	        		'queHoteles'      => 'quintanaroo',
        			'urlFotosHoteles' => 'https://www.turista.com.mx/img/hoteles/f/',
        			'dirFotosHoteles' => '/paginas/turista.me/hoteles/f/',
        			'urlMx'           => 'https://quintanaroo.turista.com.mx/',
        	        'apiUrl'          => 'api.quintanaroo.turista.com.mx',
        			'externos'        =>
        			array(
        					array(
        							'siteName' => 'Turista Yucatán',
        							'estadoName' => 'Yucatán',
        							'url' => 'https://yucatan.turistamexico.com'
        					),
        					array(
        							'siteName' => 'Turista Chiapas',
        							'estadoName' => 'Chiapas',
        							'url' => 'https://chiapas.turistamexico.com'
        					),
        					array(
        							'siteName' => 'Turista Puebla',
        							'estadoName' => 'Puebla',
        							'url' => 'https://puebla.turistamexico.com'
        					),
        					array(
        							'siteName' => 'Turista Estado de México',
        							'estadoName' => 'Estado de México',
        							'url' => 'https://edomexico.turistamexico.com'
        					),
        					array(
        							'siteName' => 'Turista México',
        							'estadoName' => 'México',
        							'url' => 'https://www.turistamexico.com'
        					),
        					array(
        							'siteName' => 'Turista Estados Unidos',
        							'estadoName' => 'Estados Unidos',
        							'url' => 'https://usa.turistamexico.com'
        					),
        			
        			)
        	);
        
        
        
        
        
        return $siteConfig;

	}

}