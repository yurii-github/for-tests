<?php
namespace mytest;

use app\models\User;
use framework\Application;

class UserModelTest extends \PHPUnit_Framework_TestCase
{

	private $data; //user fixture
	private $user;
	
	public function setUp()
	{
		// acceptable fixture
		$this->data = [
			'username' => 'Unique_1',
			'password' => 'pass$#2 12',
			'email' => 'email@site.com',
			'fullname' => 'name surname',
			'sex' => 'male'
		];
		

		$this->user = new User();
	}
	
	public function tearDown()
	{
		Application::db()->getPdo()->query('DELETE FROM users');
	}

	public function testSuccessDataFixture()
	{
		$this->user->register($this->data);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 100
	 */
	function testRegister_Username_Empty()
	{
		$this->data['username'] = '';
		$this->user->register($this->data);
	}
	
	function providerNonAlphaChars()
	{
		return [['$'], [' '],['/'] ];
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 101
	 * @dataProvider providerNonAlphaChars
	 */
	function testRegister_Username_NonAlphanumeric($c)
	{
		$this->data['username'] .= $c;
		$this->user->register($this->data);
	}

	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 102
	 */
	function testRegister_Username_MinLength()
	{
		$min = 3;
		$this->data['username'] = substr($this->data['username'], 0, $min-1);
		$this->user->register($this->data);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 102
	 */
	function testRegister_Username_MaxLength()
	{
		$max = 20;
		$dummy = '123456789012345678901234567890';
		$this->data['username'] = substr($dummy, 0, $max+1);
		$this->user->register($this->data);
	}
	

	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 103
	 */
	function testRegister_Username_Unique()
	{
		$this->user->register($this->data);
		$this->user->register($this->data);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 110
	 */
	function testRegister_Password_NotEmpty()
	{
		$this->data['password'] = '';
		$this->user->register($this->data);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 111
	 */
	function testRegister_Password_NotSameAsUsername()
	{
		$this->data['password'] = $this->data['username'];
		$this->user->register($this->data);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 112
	 */
	function testRegister_Password_MinLength()
	{
		$min = 3;
		$this->data['password'] = substr($this->data['password'], 0, $min-1);
		$this->user->register($this->data);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 112
	 */
	function testRegister_Password_MaxLength()
	{
		$max = 16;
		$dummy = '123456789012345678901234567890';
		$this->data['password'] = substr($dummy, 0, $max+1);
		$this->user->register($this->data);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 120
	 */
	function testRegister_Email_NotEmpty()
	{
		$this->data['email'] = '';
		$this->user->register($this->data);
	}
	
	function providerFailEmails()
	{
		return [ ['cool @mail.com'], ['s@me@mail.com'], ['not-realy-mail.com'] ];
	}
	
	/**
	 * @dataProvider providerFailEmails
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 121
	 */
	function testRegister_Email_EmailPattern($email)
	{
		$this->data['email'] = $email; 
		$this->user->register($this->data);
	}
	
	// ----------------------
	
	function testRegister_Fullname_StartEndWhitespacesTrimmed()
	{
		$original = $this->data['fullname'];
		$this->data['fullname'] = ' ' . $this->data['fullname'] . ' ';
		$this->user->register($this->data);
		$this->assertEquals($original, $this->user->getUserByUsername($this->data['username'])->fullname);
	}
	
	function testRegister_Fullname_ExtraWhitespacingTrimmed()
	{
		$original = $this->data['fullname'];
		$this->data['fullname'] = implode('        ', explode(' ', $this->data['fullname']));
		$this->user->register($this->data);
		$this->assertEquals($original, $this->user->getUserByUsername($this->data['username'])->fullname);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 130
	 */
	function testRegister_Fullname_NotEmpty()
	{
		$this->data['fullname'] = '';
		$this->user->register($this->data);
	}
	
	function providerNumbers()
	{
		return [['1'],  ['123'] ];
	}

	/**
	 * @dataProvider providerNumbers
	 * @dataProvider providerNonAlphaChars
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 131
	 */
	function testRegister_Fullname_LettersSpacesOnly($c)
	{
		$this->data['fullname'] = $c . $this->data['fullname'];
		$this->user->register($this->data);
	}
	
	/**
	 * @expectedException framework\Exception
	 * @expectedExceptionCode 132
	 */
	function testRegister_Fullname_MaxLength()
	{
		$max = 255;
		$dummy = ''; $c = 0;
		do { $dummy .= 'aaaaaaaaaabbbbbbbbbb'; $c++; } while ($c < 13);

		$this->data['fullname'] = substr($dummy, 0, $max+1);
		$this->user->register($this->data);
	}
	
	function providerFullnamesSplitter()
	{
		return [
			['name surname', 'name', 'surname'],
			['name sur name', 'name', 'sur name'],
			['na me sur name', 'na', 'me sur name'],
		];
	}
	
	
	/**
	 * @dataProvider providerFullnamesSplitter
	 */
	function testRegister_Fullname_SplitterNormal($fullname, $name, $surname)
	{
		$this->data['fullname'] = $fullname;
		$this->user->register($this->data);
		$db_user = $this->user->getUserByUsername($this->data['username']);
		$this->assertEquals($name, $db_user->name);
		$this->assertEquals($surname, $db_user->surname);
	}
	
	function testRegister_Sex_DefaultMale()
	{
		$this->data['sex'] = 'asd123asd';
		$this->user->register($this->data);
		$db_user = $this->user->getUserByUsername($this->data['username']);
		$this->assertEquals('male', $db_user->sex);
	}
	

	
	function testRegister_ValidateNewValidUser()
	{
		$uuid_pattern = '/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/';

		$this->user->register($this->data);
		$db_user = $this->user->getUserByUsername($this->data['username']);//get it from db now
		//generated
		$this->assertEquals(1, preg_match($uuid_pattern, $db_user->user_id), 'user_id is not proper UUID');
		$this->assertEquals((new \DateTime())->format('Y-m-d H:i:s'), $db_user->created_date, 'Datetime not match. PHP/MySQL wrong time zone or error');
		$this->assertEquals($db_user->created_date, $db_user->updated_date);
		// posted
		$this->assertEmpty(array_diff_assoc((array)$this->user, (array)$db_user), 'Provided values doesn\'t match');
	}
	

	
	
	
}