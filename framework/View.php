<?php
namespace framework;

class View
{
	private $js = [];
	private $css = [];
	
	/**
	 * 
	 * @param string $view_file FQ name, like ROOT/app/views/controller_name/view_name.php
	 * @param unknown $data
	 */
	public function render($view_file, array $data = [])
	{
		if (!file_exists($view_file)) {
			throw new \Exception("View file [$view_file] does not exist");
		}
		
		extract($data, EXTR_OVERWRITE);
		ob_start();
		include $view_file;
		$content = ob_get_clean();
		ob_end_clean();
		
		return $this->renderLayout($content);
	}
	
	private function renderLayout($content)
	{
		$layout_file = Application::$basePath . '/layout.php';
		if (!file_exists($layout_file)) {
			throw new \Exception("Layout file [$layout_file] does not exist");
		}

		ob_start();
		include $layout_file;
		return ob_get_clean();
	}
	
	
	
	// - - - - - LAYOUT stuff
	
	public function addCss($relpath)
	{
		$this->css[] = $relpath;
	}
	
	public function head()
	{
		$echo = '';
		
		foreach ($this->css as $c) {
			$echo = '<link href="'.$c.'" rel="stylesheet" type="text/css" />';
		}
		
		foreach ($this->js as $j) {
			$echo = '<script type="text/javascript" src="'.$j.'"></script>';
		}
		
		return $echo;
	}
	
}