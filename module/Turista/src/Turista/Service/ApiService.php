<?php 
namespace Turista\Service;

use Zend\Http\Request;
use Zend\Http\Client as HttpClient;

class ApiService
{
    protected $config;
    protected $cache;
    protected $headers;
    protected $client;
    
    public function __construct($config, $cache)
    {
        $this->config = $config;
        $this->cache  = $cache;
        $this->headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ); 
        $this->client = new HttpClient();
    }
    
    private function getApiContent($apiUrl) {
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept: application/json"
            )
        );
        $context = stream_context_create($opts);
        $content = @file_get_contents($apiUrl, false, $context); //con la @ no manda el warning a php
        
        if ($content) {
            $retval = json_decode($content, true);
        } else {
            $retval = false;
        }
        
        return $retval;
    }
    
    public function getApiData($service, $options, $fromCache=true)
    {
        $apiUrl = 'http://' . $this->config['apiUrl'] . '/' . $service . '/' . $options;
//         echo '<br>' . $apiUrl;
        $ss = new \Hoteles\Service\StringService();
        $cacheId = $service . '-' . $ss->sanearString($options);
        $cacheId = str_replace('=', '-', $cacheId);
        
        if ($fromCache) {
            $retval = $this->cache->getItem($cacheId);
            if (!empty($retval)) {
                return $retval;
            }
        }
        
       $retval = $this->getApiContent($apiUrl);  
       unset($retval['_links']);
       $this->cache->setItem($cacheId, $retval);
        
        //         \Zend\Debug\Debug::dump($retval);
        return $retval;
    }
    
    public function getCollection($service, $options='', $onlyEmbedded = true, $externo=null)
    {
        if (!$externo) {
            $apiUrl = 'http://' . $this->config['apiUrl'] . '/' . $service . '?' . $options;
        } else {
            $apiUrl = 'http://api.' .$externo . '.turista.com.mx/' . $service . '?' . $options; 
        }
//         echo '<br>' . $apiUrl;
        $cacheId = 'collection' . $service . '-' . md5(json_encode($options));
        $cacheId = str_replace('=', '-', $cacheId);
        if (!$onlyEmbedded) {
           $cacheId .= '-full';
        }
        if ($externo) {
            $cacheId .= '-' . $externo;
        }
       
        $retval = $this->cache->getItem($cacheId);
        if (!empty($retval)) {
            return $retval;
        }
        $retval = $this->getApiContent($apiUrl);
        
        if ($onlyEmbedded) {
            $embeddedValue = str_replace('-', '_', $service);
            $retval = $retval['_embedded'][$embeddedValue];
        }
        
        $this->cache->setItem($cacheId, $retval);
         
        return $retval;
    }
    
    public function getCollectionSinCache($service, $options='', $onlyEmbedded = true, $externo=null)
    {
        if (!$externo) {
            $apiUrl = 'http://' . $this->config['apiUrl'] . '/' . $service . '?' . $options;
        } else {
            $apiUrl = 'http://api.' .$externo . '.turista.com.mx/' . $service . '?' . $options;
        }
        //         echo '<br>' . $apiUrl;
        
    
        $retval = $this->getApiContent($apiUrl);
    
        if ($onlyEmbedded) {
            $embeddedValue = str_replace('-', '_', $service);
            $retval = $retval['_embedded'][$embeddedValue];
        }
    
        return $retval;
    }
    
    public function update($service, $id, $data)
    {
        $uri =  'http://' . $this->config['apiUrl'] . '/' . $service . '/' . $id;
        $request = new Request();
        $request->setUri($uri);
        $request->setContent(json_encode($data));
        $request->setMethod(Request::METHOD_PUT);
        $request->getHeaders()->addHeaders($this->headers);
    
        return json_decode($this->client->send($request)->getContent(), true);
        //var_dump( $client->send($request)->getContent(), true );die();
    }
    
    public function create($service, $data)
    {
        $uri = 'http://' . $this->config['apiUrl'] . '/' . $service;
        $request = new Request();
        $request->setUri($uri);
        $request->setContent(json_encode($data));
        $request->setMethod(Request::METHOD_POST);
        $request->getHeaders()->addHeaders($this->headers);
        
        return json_decode($this->client->send($request)->getContent(), true);
        //var_dump( $this->client->send($request)->getContent() );die();
    }
}