<?php
/**
 * Turistamexico (http://www.turistamexico.com)
 * Module: Hoteles
 * Controller: EstadoController
 */
namespace Hoteles\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class EstadoController extends AbstractActionController
{
	
	public function indexAction() 
	{
	   $zendCache = new \Zend\Cache\Storage\Adapter\Memory();
       $cache = new \DoctrineModule\Cache\ZendStorageCache($zendCache);
       
		$id = $this->params()->fromRoute('estadoid');
		if ($id == 'no match') {
			$this->flashmessenger()->addMessage('No se Encontró el Estado');
			return $this->redirect()->toRoute('hoteles-index');
		}
		
		$estadoNombre = $this->params()->fromRoute('nombre');
		$pageTitle = 'Hoteles en ' . $estadoNombre;
		$em = $this->getEntityManager();
		$config = $this->getServiceLocator()->get('turista-config');
		$queHoteles = $config['queHoteles'];
		$fotoService = $this->getServiceLocator()->get('hoteles-foto-service');
		
		$vistas = $em->getRepository('Hoteles\Entity\\' .$queHoteles . 'Hotelesviews')
					 ->findBy(array('estado' => $id));
		
		if (count($vistas) < 1) {
			$this->flashmessenger()->addMessage('Este estado no tiene vistas');
			$this->redirect()->toRoute('hoteles-index');
		}
		
		$hotelesEntity = 'Hoteles\Entity\\' . $queHoteles . 'Hoteles';
		$cacheId = $queHoteles.'_estado_'.$id;
		if ($cache->contains($cacheId)) {
		    $qb = $cache->fetch($cacheId);
		    die('Existe el cache');
		} else {
		    $qb = $em->createQueryBuilder();
		    $qb->select('partial h.{hotelid,bestdayid,nombre,vista,rating, lowestrate, ispromo}')
		    ->from($hotelesEntity, 'h')
		    
		    //->where('h.visible = ?1')
		    ->orderBy('h.vista','ASC')
		    ->addOrderBy('h.rating', 'DESC')
		    ->setParameter(1, '1')
		    ->setParameter(2, '0');
		    
		    
		    foreach ($vistas as $i => $vista) {  //para que obtenga todas las vistas del estado
		        $j = $i+3;
		        $qb->setParameter($j, $vista->getHviid());
		        $qb->orWhere('h.vista =?' . $j . ' AND (h.visible = ?1' . ' AND h.lowestrate > ?2)');
		    }
		    //var_dump($qb->getDQL()); die();
		    $cache->save($cacheId, $qb);
		}
		
		
		
		 
		$hoteles = new Paginator(
				new DoctrinePaginator(new ORMPaginator($qb))
		);
		
		
		
		$pagina = $this->params()->fromRoute('page');
		if ($pagina > 1) {
			$pageTitle .= ' <small>Pagina ' . $pagina . '</small>';
			$this->layout()->setVariable('actionBarPaginator', array(
					$hoteles,
					'hoteles/index/partial/paginationControl',
					'/hotelesen/' . urlencode($estadoNombre),
			));
		}
		$this->layout()->setVariable('headerSecTitle', $pageTitle);
		//$this->layout()->setVariable('headerSecRightPartial', 'noticias/noticias/partial/index-header-right');
		$this->layout()->setVariable('estadoNombre', $estadoNombre);
		$this->layout()->setVariable('actionBarPartials', array(
				'hoteles/partial/dropdown-estados',
				'hoteles/estado/partial/dropdown-estado-vistas'
		));
		$hoteles->setDefaultItemCountPerPage(36);
		$hoteles->setCurrentPageNumber($pagina);

		
		return array(
				'estadoNombre' => $estadoNombre,
				'em'           => $em,
				'config'       => $config,
				'hoteles'      => $hoteles,
				'pagina'       => $pagina,
				'vistas'       => $vistas,
				'fotoService'  => $fotoService,
				'hotelService' => $this->getServiceLocator()->get('hoteles-hotel-service'),
		);
	}
	
	
	
	private function getEntityManager()
	{
		$em = $this->getServiceLocator()
		->get('Doctrine\ORM\EntityManager');
		return $em;
	}



}
