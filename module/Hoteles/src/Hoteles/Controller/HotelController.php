<?php
/**
 * Turistamexico (http://www.turistamexico.com)
 * Module: Hoteles
 * Controller: HotelController
 */

namespace Hoteles\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HotelController extends AbstractActionController
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
    
    public function infoAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        $apiService = $this->getApiService();
        $hotel = $apiService->getApiData('hotel', $id);
        //\Zend\Debug\Debug::dump($hotel);
        $vista = $apiService->getApiData('vista', $hotel['vista']);
         return new ViewModel(array(
            'hotel' => $hotel,
            'vista' => $vista,
        ));
    }
    private function getEntityManager()
    {
        $em = $this->getServiceLocator()
        ->get('Doctrine\ORM\EntityManager');
        return $em;
    }
    
    public function infoActionOld()
    {
        $id = (int) $this->params()->fromRoute('id');
        $em = $this->getEntityManager();
        $config = $this->getServiceLocator()->get('turista-config');
        $queHoteles = $config['queHoteles'];
        $hotelesEntity = 'Hoteles\Entity\\' . $queHoteles . 'Hoteles'; 
        $hotel = $em->find($hotelesEntity, $id);
        if (!$hotel) {
            $this->flashmessenger()->addMessage('Hotel no Encontrado');
            return $this->redirect()->toRoute('hoteles-index');
        }
        
        $vista = $em->find('Hoteles\Entity\\' .$queHoteles . 'Hotelesviews', $hotel->getVista());
        $vistasEstado = $em->getRepository('Hoteles\Entity\\' . $queHoteles . 'Hotelesviews')
        ->findBy(array('estado' => $vista->getEstado()));
        $vistasNavigation = array();
        foreach ($vistasEstado as $vistaNav) {
            if ($vistaNav->getHviid() == $vista->getHviid()) {
                $active = true;
            } else {
                $active = false;
            }
            $vistasNavigation[] = array(
                'label' => $vistaNav->getHviDescEnglish(),
                'uri'   => '/hoteles/' . urlencode($vistaNav->getHviDescEnglish()),
                'active' => $active
                 
            );
        }
        
        
        $fotoService = $this->getServiceLocator()->get('hoteles-foto-service');
        $directorio = $fotoService->getDirectorioVista($vista);
        $portada = $fotoService->getPortada($hotel, 'portada');
			if ($portada) {
				$fileName = $directorio . $portada;
			} else {
				$fileName = 'http://turista.me/images/hotel/' . $hotel->getRating() . 'starshotel_normal.jpg';
			}
	    
	    
        return new ViewModel(array(
            'hotel' => $hotel,
            'vista' => $vista,
            'filePortada' => $fileName,
            'vistasNavigation' => $vistasNavigation,
        ));
    }
}