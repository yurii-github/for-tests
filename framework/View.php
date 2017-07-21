<?php
namespace framework;

class View
{
	public static function printHeader()
	{
		$headerFile = Application::$basePath . '/app/views/header.php';
		
		if (!file_exists($headerFile)) {
			throw new \Exception("header file [$headerFile] does not exist");
		}
		
		require $headerFile;
	}
	
	public static function printFooter()
	{
		$footerFile = Application::$basePath . '/app/views/footer.php';
		
		if (!file_exists($footerFile)) {
			throw new \Exception("Footer file [$footerFile] does not exist");
		}
		
		require $footerFile;
	}
	
	
	public function render($viewFile, array $data = array())
	{

		if (!file_exists($viewFile)) {
			throw new \Exception("View file [$viewFile] does not exist");
		}
		
		extract($data, EXTR_OVERWRITE);
		
		self::printHeader();
		require $viewFile;
	}

}