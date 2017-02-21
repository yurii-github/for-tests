<?php
namespace framework;

class Model
{
	public static function getPdo()
	{
		return Database::getInstance()->getPdo();
	}
	
	
}