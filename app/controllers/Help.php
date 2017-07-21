<?php
namespace app\controllers;

use framework\Controller;

class Help extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	
}