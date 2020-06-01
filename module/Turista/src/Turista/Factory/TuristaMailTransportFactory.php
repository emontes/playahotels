<?php
namespace Turista\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class TuristaMailTransportFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator) {
	    // Setup SMTP transport using PLAIN authentication over TLS
	    $transport = new SmtpTransport();
	    $options   = new SmtpOptions(array(
	    		'name'              => 'dstr.net',
	    		'host'              => 'email-smtp.us-east-1.amazonaws.com',
	    		'port'              => 587, // Notice port change for TLS is 587
	    		'connection_class'  => 'plain',
	    		'connection_config' => array(
	    				'username' => 'AKIAIWLIUP25ZI2LX54Q',
	    				'password' => 'AnIWilwyUaMEf3PK6HkkScgt26u9yO/qFQP6/lynNYsR',
	    				'ssl'      => 'tls',
	    		),
	    ));
	    $transport->setOptions($options);
	    
	    return $transport;
	}

}