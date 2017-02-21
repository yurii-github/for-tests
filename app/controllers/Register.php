<?php
namespace app\controllers;

use framework\Controller;
use framework\Application;
use framework\Application as app;
use app\models\User;
use framework\Exception as MyException;

class Register extends Controller
{

	/**
	 * json message, format allows single error at a time
	 *
	 * @property boolean success
	 * @return \stdClass
	 */
	private function getRespObj()
	{
		$resp = new \stdClass();
		$resp->success = false; // boolean, if message success or failure
		$resp->msg = ''; // anything, but just text so far
		$resp->redirect = null; // url
		$resp->data = null; // anything that is supported by your js
		return $resp;
	}

	public function actionLogin()
	{
		return $this->render('login');
	}

	public function actionAuthenticate()
	{
		$resp = $this->getRespObj();
		
		try {
			if (Application::app()->user()) { // already verified
				$resp->success = true;
				$resp->redirect = app::request()->url('profile');
				return json_encode($resp, JSON_UNESCAPED_UNICODE);
			}
			User::login(app::request()->post('username'), app::request()->post('password'), @$_SERVER['REMOTE_ADDR']);
			$resp->success = true;
			$resp->redirect = app::request()->url('profile');
		} catch (MyException $e) {
			$resp->msg = $e->getMessage();
		} catch (\Exception $e) {
			trigger_error($e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine(), E_USER_ERROR);
			$resp->msg = app::t('Unhandled error. If this error persist, please notify admin');
		}
		
		return json_encode($resp, JSON_UNESCAPED_UNICODE);
	}

	public function actionLogout()
	{
		if (! Application::user()) {
			// hijack js
			return $this->redirect('register/login');
		}
		Application::user()->logout();
		return $this->redirect('register/login');
	}

	public function actionIndex()
	{
		return $this->render('register');
	}

	public function actionRegister()
	{
		$resp = $this->getRespObj();
		$r = Application::request();
		$data = [];
		foreach (['username','password','email','fullname','sex'] as $item) {
			$data[$item] = $r->post($item);
		}
		
		try {
			$user = new User();
			$user->register($data); //register
			User::login($data['username'], $data['password']); // auto-login
			$resp->success = true;
			$resp->redirect = app::request()->url('profile'); //set redirection
		} catch (MyException $e) {
			$resp->msg = $e->getMessage();
		} catch (\Exception $e) {
			trigger_error($e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine(), $e->getCode(), E_USER_ERROR);
			$resp->msg = app::t('Unhandled error. If this error persist, please notify admin');
		}
		
		echo json_encode($resp, JSON_UNESCAPED_UNICODE);
	}

	public function actionUploadCover() // avatar
	{
		$resp = $this->getRespObj();
		$file = [];
		/*@var $user User  */
		$user = Application::user();
		try {
			if (! $user) {
				throw new Exception(app::t('Unauthorized request'), 140);
			}
			$user->saveAvatar(@$_FILES['file']);
			$resp->success = true;
		} catch (MyException $e) { // handled
			$resp->msg = $e->getMessage();
		} catch (\Exception $e) {
			trigger_error($e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine(), $e->getCode(), E_USER_ERROR);
			$resp->msg = app::t('Unhandled error. If this error persist, please notify admin');
		}
		
		return json_encode($resp, JSON_UNESCAPED_UNICODE);
	}
}