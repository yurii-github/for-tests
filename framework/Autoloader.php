<?php
namespace framework;

//
// according to www.php-fig.org/psr/psr-4, sort of
//
final class Autoloader
{
	static private $loader;

	private function load($cls)
	{
		$file = dirname(__DIR__). '/'. str_replace('\\', '/', $cls) .'.php';
		if (file_exists($file) && is_readable($file)) {
			require_once $file;
		} else {
			// TODO: file not available
		}
	}

	private function __construct()
	{
		spl_autoload_register(array($this, 'load'), false, true);
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