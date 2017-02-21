<?php
namespace framework;

//
// according to www.php-fig.org/psr/psr-4
//
final class Autoloader
{
	static private $loader;
	
	private function load($cls)
	{
		$file = dirname(__DIR__). '/'. str_replace('\\', '/', $cls) .'.php';
		@include $file;
	}
	
	private function __construct()
	{
		spl_autoload_register([$this, 'load'], false, true);
	}
	
	static public function getLoader()
	{
		if (!(self::$loader instanceof Autoloader)) {
			self::$loader = new Autoloader();
		}
		
		return self::$loader;
	}
}
return Autoloader::getLoader();