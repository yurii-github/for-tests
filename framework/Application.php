<?php
namespace framework;

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	//handled by us
	$errors = array(E_WARNING=>'E_WARNING', E_ERROR=>'E_ERROR', E_NOTICE=>'E_NOTICE',
		E_USER_WARNING => 'E_USER_WARNING', E_USER_NOTICE=>'E_USER_NOTICE',
		E_USER_ERROR=>'E_USER_ERROR', E_USER_DEPRECATED=>'E_USER_DEPRECATED');

	if (!empty($errors[$errno])) {
		error_log("{$errors[$errno]}  :::  $errstr \n {$errfile} : $errline");
		return true; //handled
	}

	return false; //execute default handler
}

class Application
{
	private static $self;
	public static $basePath;
	private $request;
	private $controller;

	private function __construct(){}

	public static function getInstance()
	{
		return self::$self;
	}

	/**
	 * @return Request
	 */
	public static function request()
	{
		return self::getInstance()->request;
	}

	/**
	 * only one way to init application
	 * @param boolean $execute
	 */
	public static function run($execute = true)
	{
		try {
			
			if (self::$self instanceof Application) {
				throw new \Exception('Application can be run only once!');
			}
			
			if ($execute) {
				set_error_handler('\framework\myErrorHandler');
			}
				
			self::$self = new Application();
			$app = self::getInstance();
			self::$basePath = dirname(__DIR__); //application dir
			$app->request = new Request();

			$act = $app->request->resolve(); //[action, controller, params(raw!)]
			$cmd = self::getInstance()->commandMaps();
			$cmd = @$cmd[$act['command']];

			if (empty($cmd) || !class_exists($cmd['controller'])) {
				$cmd['action'] = 'actionIndex';
				$cmd['controller'] = '\app\controllers\Help';
			}

			if (!$execute) {//for testing
				return;
			}
			
			$app->controller = new $cmd['controller']();
			$app->controller->{$cmd['action']}($act['params']); // PHP7 compatability fix

			View::printFooter();
			
		} catch(\Exception $e) {
			View::printHeader();
			fwrite(STDERR, 'Caught Error: ' . $e->getMessage());
		}
	}

	private function commandMaps()
	{
		return array(
			'?' => array('controller' => '\app\controllers\Help', 'action' => 'actionIndex'),
			'print' => array('controller' => '\app\controllers\PrintTable', 'action' => 'actionIndex'),
			
		);
	}

}