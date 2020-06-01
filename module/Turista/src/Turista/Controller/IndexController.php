<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Turista for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Turista\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    private function getEntityManager()
    {
    	$em = $this->getServiceLocator()
    	->get('Doctrine\ORM\EntityManager');
    	return $em;
    }
    
    public function indexAction()
    {
        return array();
    }

    
    
}
