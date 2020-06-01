<?php
/**
 * Turistamexico (http://www.turistamexico.com)
 * Module: Hoteles
 * Controller: VistaController
 */
namespace Hoteles\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class VistaController extends AbstractActionController
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
        $id = $this->params()->fromRoute('vistaid');
        if ($id == 'no match') {
            $this->flashmessenger()->addMessage('No se Encontró la Ubicación');
            return $this->redirect()->toRoute('hoteles-disponibles');
        }
        $vistaNombre = $this->params()->fromRoute('nombre');
        $estado = $this->params()->fromRoute('estadoid');
        $pagina = $this->params()->fromRoute('page');
        if ($pagina > 1) {
            //$pageTitle .= ' <small>Pagina ' . $pagina . '</small>';
            	
        }
        $apiService = $this->getApiService();
        $service = new \Hoteles\Service\VistaService();
        $vista = $apiService->getApiData('vista', '0.' . $vistaNombre . '.english');
        $options = 'vistaid=' . $id . '&order=index';
        $hijas = array();
        if ($vista['hijas'] <> '') {
            $options .= '&hijas=' . $vista['hijas'];
            $hijas = $apiService->getCollection('vista', 'hijasde=' . $vista['hviid']);
        }
        if ($pagina > 1) {
            $options .= '&page=' . $pagina;
        }
        //var_dump($options);
        $collection = $apiService->getCollection('hotel', $options, false);
        $pages = $service->makePaginatorBootStrap($collection, 'hotels/' . $vistaNombre);
        $hoteles = $collection['_embedded']['hotel'];
        
        return array (
            'vistaNombre'  => $vistaNombre,
            'estado'       => $estado,
            'hoteles'      => $hoteles,
            'pages'        => $pages,
        );
    }
    
	public function indexActionOld()
	{
		$id = $this->params()->fromRoute('vistaid');
		if ($id == 'no match') {
			$this->flashmessenger()->addMessage('No se Encontró la Ubicación');
			return $this->redirect()->toRoute('hoteles-index');
		}
		$vistaNombre = $this->params()->fromRoute('nombre');
		$estado = $this->params()->fromRoute('estadoid');
		$em = $this->getEntityManager();
		$config = $this->getServiceLocator()->get('turista-config');
		$queHoteles = $config['queHoteles'];
		$fotoService = $this->getServiceLocator()->get('hoteles-foto-service');
		$hotelesEntity = 'Hoteles\Entity\\' . $queHoteles . 'Hoteles';
		
		$qb = $em->createQueryBuilder();
		$qb->select('partial h.{hotelid,bestdayid,nombre,vista, rating, lowestrate, ispromo, direccion}')
		//->orderBy('h.vista','ASC')
		->addOrderBy('h.rating', 'DESC')
		->from($hotelesEntity, 'h');
		
		$qchilds = $em->createQueryBuilder();
		//$qchilds->select('partial v.{hviid, parentid}')
		$qchilds->select('v')
		->from('Hoteles\Entity\\' . $queHoteles . 'Hotelesviews', 'v')
		->where('v.parentid = ?1')
		->setParameter(1, $id);
		$childs = $qchilds->getQuery()->execute();
		
		if (count($childs) == 0) {
			$qb->where('h.vista = ?1')
			->andWhere('h.lowestrate > 0')
			->setParameter(1,  $id);
		} else {
			foreach ($childs as $i => $child) {
				$j = $i;
				$qb->setParameter($j, $child->getHviid());
				$qb->orWhere('h.vista=?' . $j . ' AND (h.visible = 1 AND h.lowestrate > 0)');
				
			}
		}
		
		$hoteles = new Paginator(
				new DoctrinePaginator(new ORMPaginator($qb))
		);
		$pagina = $this->params()->fromRoute('page');
		if ($pagina > 1) {
			//$pageTitle .= ' <small>Pagina ' . $pagina . '</small>';
			$this->layout()->setVariable('actionBarPaginator', array(
					$hoteles,
					'hoteles/index/partial/paginationControl',
					'/hoteles/' . urlencode($vistaNombre),
			));
		}
		$hoteles->setDefaultItemCountPerPage(36);
		$hoteles->setCurrentPageNumber($pagina);
		$estadoRow = $em->getRepository('Hoteles\Entity\\' . $queHoteles . 'Estados')->findOneBy(array('id' => $estado));
		$this->layout()->setVariable('estadoNombre', $estadoRow->getNombre());
		$this->layout()->setVariable('actionBarPartials', array(
		    'hoteles/partial/dropdown-estados',
		    'hoteles/estado/partial/dropdown-estado-vistas'
		));
		
		return array (
			'vistaNombre'  => $vistaNombre,
			'estado'       => $estado,
			'hoteles'      => $hoteles,
			'pagina'       => $pagina,
			'fotoService'  => $fotoService,
			'hotelService' => $this->getServiceLocator()->get('hoteles-hotel-service'),
			'em'           => $em,
			'config'       => $config,
		);
	}
	
	
	
	private function getEntityManager()
	{
		$em = $this->getServiceLocator()
		->get('Doctrine\ORM\EntityManager');
		return $em;
	}
}
