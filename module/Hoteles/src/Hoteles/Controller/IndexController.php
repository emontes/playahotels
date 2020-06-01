<?php
/**
 * Turistamexico (http://www.turistamexico.com)
 * Module: Hoteles
 * Controller: IndexController
 */
namespace Hoteles\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Hoteles\Service\VistaService;

class IndexController extends AbstractActionController
{
    private function getCache()
    {
        $serCache = new \Turista\Service\CacheService();
        $cache = $serCache->getCache('hoteles', '43200'); // 12 horas de cache;
        return $cache;
    }
    private function getApiService()
    {
       $config = $this->getServiceLocator()->get('turista-config');
        $cache = $this->getCache();
        $apiService = new \Turista\Service\ApiService($config, $cache);
       
        return $apiService;
    }
    
    public function indexAction()
    {
        $pageTitle = 'Hotels';
        $pagina = $this->params()->fromRoute('page');
        if ($pagina > 1) {
			$pageTitle .= ' <small>Pagina ' . $pagina . '</small>';
			
		}
        $config = $this->getServiceLocator()->get('turista-config');
        $apiService = $this->getApiService();
        $service = new \Hoteles\Service\VistaService();
        //$options = 'vistaid=7';
        $options = '';
        if ($pagina>1) {
            $options = 'page=' . $pagina;
        }
        $collection = $apiService->getCollection('hotel', $options, false);
        $pages = $service->makePaginatorBootStrap($collection, 'hotels');
        $hoteles = $collection['_embedded']['hotel'];
        
        return array(
            'config'  => $config,
            'pages'   => $pages,
			'hoteles' => $hoteles,
        );
    }
    
	public function indexActionOdl()
	{
	    
		$pageTitle = 'Hoteles';
		
		$em = $this->getEntityManager();
		$config = $this->getServiceLocator()->get('turista-config');
		$fotoService = $this->getServiceLocator()->get('hoteles-foto-service');
		$queHoteles = $config['queHoteles'];
		$hotelesEntity = 'Hoteles\Entity\\' . $queHoteles . 'Hoteles';
		$qb = $em->createQueryBuilder();
		$qb->select('partial h.{hotelid,bestdayid,nombre,vista,visible,rating, lowestrate, ispromo, direccion}')
		//$qb->select('h')
		   ->from($hotelesEntity, 'h')
		   ->where('h.visible = :visible')
		   ->andWhere('h.lowestrate > 0')
		   //->addOrderBy('h.vista','ASC')
		   ->addOrderBy('h.rating', 'DESC')
			->setParameter('visible', '1');
		   
		$hoteles = new Paginator(
			new DoctrinePaginator(new ORMPaginator($qb))
		);
		
		$pagina = $this->params()->fromRoute('page');
		if ($pagina > 1) {
			$pageTitle .= ' <small>Pagina ' . $pagina . '</small>';
			$this->layout()->setVariable('actionBarPaginator', array(
					$hoteles,
					'hoteles/index/partial/paginationControl',
					'/hoteles',
			));
		}
		$this->layout()->setVariable('headerSecTitle', $pageTitle);
		//$this->layout()->setVariable('headerSecRightPartial', 'noticias/noticias/partial/index-header-right');
		$this->layout()->setVariable('actionBarPartials', array('hoteles/partial/dropdown-estados'));
		$hoteles->setDefaultItemCountPerPage(36);
		$hoteles->setCurrentPageNumber($pagina);
		if ($pagina > 1){
			
		}
		$hotelService = $this->getServiceLocator()->get('hoteles-hotel-service');
		return array(
			'em'      => $em,
			'config'  => $config,
			'hoteles' => $hoteles,
			'pagina'  => $pagina,
			'fotoService' => $fotoService,
			'hotelService' => $hotelService,
			'pageTitle' => $pageTitle,
		);
	}
	
	private function getEntityManager()
	{
		$em = $this->getServiceLocator()
		->get('Doctrine\ORM\EntityManager');
		return $em;
	}
}