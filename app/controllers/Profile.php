<?php
namespace app\controllers;

use framework\Controller;
use framework\Application;

class Profile extends Controller
{

	public function actionIndex()
	{
		if (! Application::user()) {
			return $this->redirect('register/login');
		}
		
		// allowed, do stuff
		return $this->render('index', [
			'user' => Application::user()
		]);
	}
}

/*
 * sets cp1251 in windows. FAIL
 * function lang($lang)
 * {
 * $domain = 'default';
 * setlocale(LC_ALL, ['uk_UA', 'Ukrainian']);
 * bindtextdomain($domain, Application::$basePath . '/locale/');
 * bind_textdomain_codeset($domain, 'UTF-8');
 * textdomain($domain);
 * }
 */