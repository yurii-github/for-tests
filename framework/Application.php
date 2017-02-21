<?php
namespace framework;

use framework\interfaces\IUser;

// stupid, but ok for testing
//
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	//handled by us
	$errors = [E_WARNING=>'E_WARNING', E_ERROR=>'E_ERROR', E_NOTICE=>'E_NOTICE', 
		E_USER_WARNING => 'E_USER_WARNING', E_USER_NOTICE=>'E_USER_NOTICE',
		 E_USER_ERROR=>'E_USER_ERROR', E_USER_DEPRECATED=>'E_USER_DEPRECATED'];
	
	if (!empty($errors[$errno])) {
		error_log("{$errors[$errno]}  :::  $errstr \n {$errfile} : $errline");
		return true; //handled
	}
	
	return false; //execute default handler
}

final class Application // seal it. cannot be extended, ok for test
{
	//i18n
	public static $lang;//en_US ...
	private static $messages = [];
	
	public $params;
	private static $self;
	public static $basePath;
	private $request;
	private $controller;
	private $view;
	private $user;
	static public $counter=0;
	
	private function __construct(){} // limited to construct only in run() function
	
	private static function getInstance()
	{
		self::$counter++;//request counter
		return self::$self;
	}
	
	public static function app()
	{
		return self::getInstance();
	}
	
	/**
	 * @return Request 
	 */
	public static function request()
	{
		
		return self::getInstance()->request;
	}
	
	/**
	 * @return IUser
	 */
	public static function user()
	{
		return self::getInstance()->user;
	}
	
	/**
	 * @return Database
	 */
	public static function db()
	{
		return self::getInstance()->db;
	}
	
	
	/**
	 * gets translated message if it exists
	 * @param string $msg message to translate
	 * @param string $category message category (different file)
	 * @param array $data data to pass to translated message by anchors, format [anchor=>value,...]
	 * @return translated message
	 */
	public static function t($msg, $category = 'messages', array $data = [])
	{
		$file = self::$basePath . '/locale/' . self::$lang . '/' . $category . '.php';
		
		if (!empty(self::$messages[$category])) { //check if already loaded, then just return
			$msg = self::$messages[$category][$msg];
		} elseif ( (self::$lang == 'en_Gb' || empty(self::$lang)) || !is_readable($file) ) { // cannot load or default lang is selected - force no translation
			// nothing to do
		} elseif (is_readable($file)) { //load translation
			self::$messages[$category] = require_once $file;
			$msg = self::$messages[$category][$msg];
		}
		
		return str_replace(array_keys($data), array_values($data), $msg);
	}
	
	
	private function setLanguage()
	{
		$secure = function($cookie_raw) { return preg_replace("/[^a-zA-Z_]+/", "", $cookie_raw); };
		
		// set from query string
		if (!empty($this->request()->get('lang'))) {
			self::$lang = $secure($this->request()->get('lang'));
			if (!empty($_COOKIE['language']) && self::$lang != $_COOKIE['language']) {//update cookie if different
				$_COOKIE['language'] = self::$lang; 
			}
			return;
		}
		
		//set from cookie
		if (!empty($_COOKIE['language'])) {
			self::$lang = $secure($_COOKIE['language']);
			return;
		}
		
		//default
		self::$lang = 'en_GB';
	}
	
	/**
	 * only one way to init application
	 * @param arrau $cfg
	 * @param boolean $execute
	 * @throws \Exception
	 * @throws Exception
	 */
	public static function run($cfg, $execute = true)
	{
		try {
			if (self::$self instanceof Application) {
				throw new \Exception('Application can be run only once!');
			}
			if (!class_exists($cfg['user'])) {
				throw new Exception("Class [{$cfg['user']}] does not exist");
			}
			
			if (!session_start()) {
				throw new \Exception('Session cannot be started. Authentication is not possible');
			}
			
			if ($execute) {//for dummy testing
				set_error_handler('framework\myErrorHandler');
			}
			
			self::$self = new Application();
			$app = self::getInstance();
			$app->params = @$cfg['params'];
			self::$basePath = $cfg['basePath'];
			
			$app->db = Database::getInstance($cfg['db']);//init
			if (!in_array('framework\interfaces\IUser', class_implements($cfg['user']))) {
				throw new Exception("[{$cfg['user']}] must implement IUser interface");
			}
			$app->user = $cfg['user']::validate();
			$app->request = new Request($cfg['request']); // init
			$app->setLanguage();
			
			if (!$execute) {//for dummy testing
				return;
			}
			
			$act = $app->request->parseUrl();
			$app->controller = new $act['controller'];
			$rendered_page = $app->controller->{$act['action']}();
			
			echo $rendered_page; //finished
			
		} catch(\Exception $error) {
			error_log($error->getMessage());
			include $cfg['error_view'];
		}
	}
	
	
}