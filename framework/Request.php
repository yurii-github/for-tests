<?php
namespace framework;

class Request
{

	public function getRawParams()
	{
		
		$params = array();
		if (isset($_SERVER['argv'])) {
			$params = $_SERVER['argv'];
			array_shift($params);
		}
	
		return $params;
	}
	
	
	public function resolve()
	{
		$rawParams = $this->getRawParams();
		$command = '';
		$params = array();
		
		if (isset($rawParams[0])) {
			$command = $rawParams[0];
			array_shift($rawParams);
		}

		foreach ($rawParams as $param) {
			if (preg_match('/^--(\w+)(?:=(.*))?$/', $param, $matches)) {
				$name = $matches[1];
				$params[$name] = isset($matches[2]) ? $matches[2] : true;
			} else {
				$params[] = $param;
			}
		}

		return array('command' => $command, 'params' => $params);
	}
}