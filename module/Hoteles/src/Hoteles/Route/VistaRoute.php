<?php
namespace Hoteles\Route;

use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Http\RouteMatch;

class VistaRoute implements RouteInterface 
{
	protected $options;
	protected $apiService;
	
	public function __construct($options = array(), $apiService)
	{
		$this->options = $options;
		$this->apiService = $apiService;
		
	}
	
	public function match(Request $request)
	{
		if (!method_exists($request, 'getUri')) {
			return null;
		}
		
		
		$uri = $request->getUri();
		$path = $uri->getPath();
		$route = $this->options['route'];
		$page = 1;
		
		
		$finalMatchOptions = $this->options['defaults'];
		//echo '<br>finalMatchOptions inicial';var_dump($finalMatchOptions);
		if (!preg_match("!^$route$!", $path, $matches)) {
			return null;
		} 
		//echo '<br> Matches:'; var_dump($matches);
		$vistaArray = explode('/', $matches['1']);
		//echo '<br> vistaArray: '; var_dump($vistaArray);
		if ($vistaArray['0'] <> 'pag') { //para saber si está paginando la página princiapl de hoteles
			$vistaNombre = $vistaArray['0'];
			if (isset($vistaArray['1'])) {
				if ($vistaArray['1'] == 'pag') {
					$page = $vistaArray['2'];
				}
			}
			$row = $this->apiService->getApiData('vista', '0.' . $vistaNombre . '.english');
			//\Zend\Debug\Debug::dump($row);
			if (!isset($row['hviid'])) {
				$finalMatchOptions['nombre'] = $vistaNombre;
				$finalMatchOptions['vistaid'] = 'no match';
				$finalMatchOptions['page'] = $page;
			} else {
				$finalMatchOptions['nombre'] = $row['hvi_desc_english'];
				$finalMatchOptions['vistaid']     = $row['hviid'];
				$finalMatchOptions['estadoid'] = $row['estado'];
				$finalMatchOptions['page']   = $page;
			}
		} else {
			$finalMatchOptions = array(
				'controller' => 'hoteles-index-controller',
				'action'     => 'index',
				'page'       => $vistaArray['1']
			);
		}
		
		$routeMatch = new RouteMatch($finalMatchOptions, strlen($path));
		//var_dump($routeMatch);die();
		return $routeMatch;
	}
	
	public function assemble(array $params = null, array $options = null)
	{
		//return '';
	}
	
	static public function factory($options = array())
	{
		return new static();
	}
	
	public function getAssembledParams()
	{
		return;
	}


}