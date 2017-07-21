<?php
namespace framework;

class Request
{

	private $cfg;

	public function __construct($cfg)
	{
		$this->cfg = $cfg;
	}

	public function isPost()
	{
		return (strtolower($_SERVER['REQUEST_METHOD']) == 'post' ? true : false);
	}
	
	public function isGet()
	{
		return (strtolower($_SERVER['REQUEST_METHOD']) == 'get' ? true : false);
	}
	
	public function get($name)
	{
		return isset($_GET[$name]) ? $_GET[$name] : null;
	}

	public function post($name)
	{
		return isset($_POST[$name]) ? $_POST[$name] : null;
	}

	/**
	 * returns current requested url
	 */
	public function getUrl()
	{
		return @$_SERVER['REQUEST_URI'];
	}
	
	/**
	 * dummy build response url, only function is to prepend language to query string as &lang=...
	 * @param string $action just this to url as ?r={action}
	 */
	public function url($action)
	{
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?r=$action". (!empty(Application::$lang) ? '&lang='.Application::$lang : '');
	}
	
	public function parseUrl()
	{
		$q = $this->get('r');
		$ns = (! empty($this->cfg['namespace']) ? $this->cfg['namespace'] . '\\' : '');
		$action_pre = 'action';
		$current = [
			'controller' => $ns . ucfirst($this->cfg['defaultController']),
			'action' => $action_pre.ucfirst($this->cfg['defaultAction'])
		];
		
		if (! empty($q)) { // use defaults
			$s = explode('/', $q);
			switch (count($s)) {
				case 1:
					$current['controller'] = $ns.ucfirst($s[0]);
					break;
				case 2:
					$current['controller'] = $ns.ucfirst($s[0]);
					$current['action'] = $action_pre.ucfirst($s[1]);
					break;
			}
		}
		
		if (! class_exists($current['controller'])) {
			throw new \Exception("Controller [{$current['controller']}] does not exist");
		}
		
		if (! method_exists($current['controller'], $current['action'])) {
			throw new \Exception("Controller method [{$current['controller']}:{$current['action']}] does not exist");
		}
		
		return $current;
	}
}