<?php
namespace mytest;

use app\controllers\Register;
use framework\Application as app;
use app\models\User;

class RegisterControllerTest extends \PHPUnit_Framework_TestCase
{

	private $response; // default json response object
	private $ctrl;
	private $data;
	
	public function setUp()
	{
		$resp = new \stdClass();
		$resp->success = false; // boolean, if message success or failure
		$resp->msg = ''; // anything, but just text so far
		$resp->redirect = null; // url
		$resp->data = null; // anything that is supported by your js
		$this->response = $resp;
		
		$this->ctrl = new Register();
		
		// acceptable fixture
		$this->data = [
			'username' => 'Unique_1',
			'password' => 'pass$#2 12',
			'email' => 'email@site.com',
			'fullname' => 'name surname',
			'sex' => 'male'
		];
		
		(new User())->register($this->data);
	}
	
	function tearDown()
	{
		app::db()->getPdo()->query('DELETE FROM users');
	}

	function providerLanguages()
	{
		return [
			[
				'uk_UA'
			],
			[
				'en_Gb'
			]
		];
	}

	/**
	 * @dataProvider providerLanguages
	 */
	function test_ActionAuthenticate_NoUsernameNoPassword($lang)
	{
		app::$lang = $lang;
		$this->response->msg = app::t("Username and Password cannot be empty");
		$this->assertEquals(json_encode($this->response, JSON_UNESCAPED_UNICODE), $this->ctrl->actionAuthenticate());
	}
	
	/**
	 * @dataProvider providerLanguages
	 */
	function test_ActionAuthenticate_WrongUsernamePassword($lang)
	{
		app::$lang = $lang;

		$_POST['username'] = 'z';
		$_POST['password'] = 'x';
		$this->response->msg = app::t('Username or Password is wrong');
		$this->assertEquals(json_encode($this->response, JSON_UNESCAPED_UNICODE), $this->ctrl->actionAuthenticate());
	}
	

	
}
