<?php
namespace mytest;

use app\models\User;
use framework\Application;

class UserModel_AvatarTest extends \PHPUnit_Framework_TestCase
{
	private $avatar;
	private $user;
	
	public function setUp()
	{
		// acceptable fixture
		$this->avatar =
			['name'=>'not used',
			'tmp_name' => __DIR__ . '/avatars/success-jpg.jpg',
			'type'=>'not used',
			'size'=>@filesize(__DIR__ . '/avatars/success-jpg.jpg')];
			
		$data = [
			'username' => 'Unique_1',
			'password' => 'pass$#2 12',
			'email' => 'email@site.com',
			'fullname' => 'name surname',
			'sex' => 'male'
		];
	
		$user = new User();
		$user->register($data);
		$this->user = $user->getUserByUsername($data['username']);
	}
	
	public function tearDown()
	{
		Application::db()->getPdo()->query('DELETE FROM users');
	}
	
	
	
	// in format of $_FILE
	function provideRestrictedAvatars()
	{
		$jpg = __DIR__ . '/avatars/success-jpg.jpg';
		$gif_ext_faking = dirname(__DIR__) . '/avatars/extension-faking.jpg';
		$max_size = 2000000; //~2Mb
		
		return [
			//filename | size
			[$gif_ext_faking, @filesize($gif_ext_faking)],// extension faking
			[$jpg, $max_size+1] //jpg suppported, but more than allowed size		
		];
	}

	/**
	 * @expectedException framework\Exception
	 * @expectedCode 140
	 */
	function testSaveAvatar_NoRights_Guest()
	{
		$this->user->user_id = null;
		$this->user->saveAvatar($this->avatar);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedCode 141
	 */
	function testSaveAvatar_NoFile()
	{
		$this->user->saveAvatar([]);
	}

	/**
	 * @dataProvider provideRestrictedAvatars
	 * @expectedException framework\Exception
	 * @expectedCode 142
	 */
	function testSaveAvatar_Restricted($tmp_name, $size)
	{
		$file = ['name'=>'not used', 'tmp_name' => $tmp_name, 'type'=>'not used', 'size'=>$size];
		$this->user->saveAvatar($file);
	}
	
	
	
	
}