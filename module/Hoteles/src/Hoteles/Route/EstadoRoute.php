<?php
namespace Hoteles\Route;

use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Http\RouteMatch;

class EstadoRoute implements RouteInterface 
{
	protected $options;
	protected $estadoRepo;
	
	public function __construct($options = array(), $estadoRepo)
	{
		$this->options = $options;
		$this->estadoRepo = $estadoRepo;
		
	}
	
	public function match(Request $request)
	{
		if (!method_exists($request, 'getUri')) {
			return null;
		}
		
		
		$uri = $request->getUri();
		$path = $uri->getPath();
		$route = $this->options['route'];
		
		
		$finalMatchOptions = $this->options['defaults'];
		
		if (isset($this->options['constraints'])) {
			foreach ($this->options['constraints'] as $param => $regex) {
				$route = str_replace(":param", "($regex)", $route);
			}
		}
		
		if (!preg_match("!^$route$!", $path, $matches)) {
			return null;
		}
		$estadoArray = explode('/', $matches['1']);
		$estadoNombre = $estadoArray['1'];
		if (isset($estadoArray['2'])) {
			$page = $estadoArray['3'];
		} else {
			$page = 1;
		}
		//regresa el nombre del estado menos '/' y ':'
		//$estadoNombre = isset($matches[1]) ? str_ireplace(array('/',':'), '', $matches[1]) : '';
		
		$estadoRow = $this->estadoRepo->findOneBy(array('nombre'=>urldecode($estadoNombre)));
		if (!$estadoRow) {
			$finalMatchOptions['nombre'] = $estadoNombre;
			$finalMatchOptions['estadoid'] = 'no match';
			$finalMatchOptions['page'] = $page;
		} else {
			$finalMatchOptions['nombre'] = $estadoRow->getNombre();
			$finalMatchOptions['estadoid']     = $estadoRow->getId();
			$finalMatchOptions['page'] = $page;
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