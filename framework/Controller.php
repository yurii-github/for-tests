<?php
namespace framework;

abstract class Controller
{

	private $view;

	public function __construct()
	{
		$this->view = new View();
	}

	/**
	 *
	 * @param string $view
	 *        	single view name. cross controller views are not supported
	 * @param array $data
	 *        	['var_name' => 'value']
	 */
	public function render($view, array $data = [])
	{
		$view_subdir = 'views';
		$cls = explode('\\', get_called_class());
		$count = count($cls);
		$ctrl = strtolower($cls[$count - 1]); // last is classname
		$fq_view = Application::$basePath . '/' . $view_subdir . '/' . $ctrl . '/' . $view . '.php';
		
		return $this->getView()->render($fq_view, $data);
	}

	public function getView()
	{
		return $this->view;
	}
	
	
	public function redirect($controller)
	{
		header('Location: ' . Application::request()->url($controller));
		die;//TODO: stupid, but ok for test
	}
}