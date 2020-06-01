<?php
namespace Turista\Service;

use Zend\Cache\StorageFactory;

class CacheService
{
    public function getCache($dir, $ttl) {
        $request = new \Zend\Http\PhpEnvironment\Request();
        $httpHost = $request->getServer('HTTP_HOST');
        $host = explode('.', $httpHost);
        $cacheDir = 'data/cache/' . $host[0];
        if ($host[0] == 'turista' || $host[0] == 'www') {
            $cacheDir = 'data/cache/mexico';
        }
        
        if ($httpHost == 'www.turista.us' || $httpHost == 'turista.us') {
            $cacheDir = 'data/cache/usa';
        }
        
        $cacheDir .= '/' . $dir;
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777,true);
        }
        $cache = StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem',
                'options' => array(
                    'cache_dir' => $cacheDir,
                    'ttl' => $ttl,
                ),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
                'serializer',
            ),
        ));
        return $cache;
    }
}