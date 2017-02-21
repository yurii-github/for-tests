<?php
namespace app\models;

use framework\Model;
use framework\Exception;
use framework\interfaces\IUser;
use framework\Application;
use framework\Application as app;

/**
 * 
 * TODO: better split authentication to another class, but i's ok for test
 * 
 * 
 * @property string user_id
 * @property string username
 * @property string email
 * @property string fullname
 * @property string name
 * @property string surname
 * @property string created_date
 * @property string updated_date
 * @property string sex
 
 * @property string session
 * @property string lastaccess_date
 * @property string lastlogin_date
 */
final class User extends Model implements IUser
{

	//------------------------START AUTH INTERFACE ----->
	
	private static function password_salted($password)
	{
		$salt = 'bla-bla';
		return hash('sha512', $salt.$password);
	}
	
	private static function session_salted()
	{
		//TODO: some sort of security. no expiration etc
		$salt = 'bla-bla';
		return hash('sha512', $salt.session_id()); 
	}

	public static function validate()
	{
		$s = self::getPdo()->prepare("SELECT * FROM users WHERE session = :session AND session IS NOT NULL");
		$s->bindValue(':session', self::session_salted());
		$s->execute();
		$c = $s->fetchObject(__CLASS__);

		if ($c) {//success
			unset($c->password);//exclude password
			$c->setLastAccessDate();
		}
		return $c;
	}
	
	/**
	 * logout user of current instance
	 */
	public function logout()
	{
		$s = Model::getPdo()->prepare("UPDATE users SET session = NULL WHERE user_id = :user_id");
		$s->bindValue(':user_id', $this->user_id);
		return $s->execute();
	}
	
	public function getLoginAttempts()
	{
		$s = self::getPdo()->prepare("SELECT * FROM login_attempts WHERE user_id = :user_id");
		$s->bindValue(':user_id', $this->user_id);
		$s->execute();
		return $s->fetchAll(\PDO::FETCH_CLASS);
	}
	
	/**
	 * 
	 * @param unknown $username
	 * @param unknown $password
	 * @param string $user_ip
	 * @throws Exception on error
	 * @return \app\models\User
	 */
	public static function login($username, $password, $user_ip=null)
	{
		if (empty($username) || empty($password)) {
			//hijack js
			throw new Exception(app::t('Username and Password cannot be empty'), 200);
		}

		$s = self::getPdo()->prepare("SELECT *, IF(block_date > NOW(), TRUE, FALSE) is_blocked FROM users WHERE username = :username");
		$s->bindValue(':username', $username);
		$s->execute();
		/* @var $c User */
		$c = $s->fetchObject(__CLASS__);
		
		if (!$c) {//not an object == user does not exist
			throw new Exception(app::t('Username or Password is wrong'), 201);//faking hacker
		}
		if ($c->is_blocked) {
			$c->logLoginAttempt('blocked', $user_ip);
			throw new Exception(app::t('Too many login attempts. Block resets in 30 minutes. Try later'), 202);
		}
		if ($c->password != self::password_salted($password)) {
			self::getPdo()->beginTransaction();
			$c->setFailLoginAttempts(true);
			$c->logLoginAttempt('failed', $user_ip);
			self::getPdo()->commit();
			throw new Exception(app::t('Username or Password is wrong'), 201);
		}
		
		//success, login and password match
		self::getPdo()->beginTransaction();
		session_regenerate_id();
		$session = self::session_salted();
		$c->setFailLoginAttempts(false);//reset to 0
		$c->logLoginAttempt('logged', $user_ip);
		$c->setSession($session);
		$c->setLastLoginDate();//update access date
		self::getPdo()->commit();
		
		unset($c->is_blocked); //exclude helper
		unset($c->password); //exclude password
		
		return $c;
	}
	

	/**
	 * sets failed login attempts, and blocks account if required
	 * 
	 * @param boolean $fail_attempt default FALSE, used on success login
	 *  0 - resets, 1 - adds 1 to failed attempt. blocks account if too many attempts
	 */
	private function setFailLoginAttempts($fail_attempt=false)
	{
		$max_attempts = 3;

		$s = self::getPdo()->prepare(
<<<SQL
UPDATE users
SET
	failed_attempts = IF(:fail_attempt = FALSE, 0, failed_attempts+1),
	block_date = IF(:fail_attempt + failed_attempts > $max_attempts, ADDTIME(NOW(), '00:30:00'), NULL)
WHERE user_id = :user_id
SQL
		);
		$s->bindValue(':user_id', $this->user_id);
		$s->bindValue(':fail_attempt', $fail_attempt);
		return $s->execute();
	}
	
	/**
	 * 
	 * @param string $status failed | logged | blocked
	 * @param string $user_ip user requested ip
	 */
	private function logLoginAttempt($status, $user_ip)
	{
		$s = self::getPdo()->prepare(
<<<SQL
INSERT INTO login_attempts (user_id, status, user_ip, created_date)
VALUES (:user_id, :status, :user_ip, NOW())
SQL
		);
		$s->bindValue(':user_id', $this->user_id);
		$s->bindValue(':status', $status);
		$s->bindValue(':user_ip', $user_ip);
		return $s->execute();
	}
	

	private function setSession($session)
	{
		$s = Model::getPdo()->prepare("UPDATE users SET session = :session WHERE user_id = :user_id");
		$s->bindValue(':user_id', $this->user_id);
		$s->bindValue(':session', $session);
		return $s->execute();
	}
	
	private function setLastAccessDate()
	{
		$s = Model::getPdo()->prepare("UPDATE users SET lastaccess_date = NOW() WHERE user_id = :user_id");
		$s->bindValue(':user_id', $this->user_id);
		return $s->execute();
	}
	
	private function setLastLoginDate()
	{
		$s = Model::getPdo()->prepare("UPDATE users SET lastlogin_date = NOW() WHERE user_id = :user_id");
		$s->bindValue(':user_id', $this->user_id);
		return $s->execute();
	}
	
	// <-----------------------------------END
	
	
	
	
	
	/**
	 * 
	 * @param string $username
	 * @return User | FALSE
	 */
	public function getUserByUsername($username)
	{
		$s = Model::getPdo()->prepare('SELECT * FROM users WHERE username = :username');
		$s->bindValue(':username', $username);
		$s->execute();
		return $s->fetchObject(__CLASS__);
	}


	/**
	 * 
	 * @param unknown $data
	 * @throws Exception handled
	 * @throws \Exception other unhandled
	 */
	public function register($data)
	{
		// apply filter rules
		//username
		if (empty($data['username'])) {
			throw new Exception(app::t('Username cannot be empty'), 100);
		}
		if (! preg_match('/^[\w\p{Cyrillic}]+$/u', $data['username'])) {
			throw new Exception(app::t('Username can contain only letters, numbers, underscore'), 101);
		}
		if ((strlen($data['username']) < app::app()->params['username_len_min']) || (strlen($data['username']) > app::app()->params['username_len_max'])) {
			throw new Exception(app::t('Username length must be {min}-{max} characters long', 'messages', 
				['{min}'=>app::app()->params['username_len_min'],'{max}'=>app::app()->params['username_len_max']]), 102);
		}
		if ($this->getUserByUsername($data['username']) instanceof User) {
			throw new Exception(app::t('This username is taken. Please select another one'), 103);
		}
		// password
		if (empty($data['password'])) {
			throw new Exception(app::t('Password cannot be empty'), 110);
		}
		if ($data['username'] == $data['password']) {
			throw new Exception(app::t('Password cannot be same as username'), 111);
		}
		if ((strlen($data['password']) < app::app()->params['password_len_min']) || (strlen($data['password']) > app::app()->params['password_len_max'])) {
			throw new Exception(app::t('Password length must be {min}-{max} characters long','messages',
				['{min}'=>app::app()->params['password_len_max'],'{max}'=>app::app()->params['password_len_max']]), 112);
		}
		//email
		if (empty($data['email'])) {
			throw new Exception(app::t('Email cannot be empty'), 120);
		}
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			throw new Exception(app::t('Please provide valid email'), 121);
		}
		//fullname
		$data['fullname'] = trim($data['fullname']); //trim start and end
		$data['fullname'] = preg_replace('/ +/', ' ', $data['fullname']); // no multispace
		if (empty($data['fullname'])) {
			throw new Exception(app::t('Fullname cannot be empty'), 130);
		}
		if (! preg_match('/^[\p{Cyrillic}a-zA-Z ]+$/u', $data['fullname'])) {
			throw new Exception(app::t('Fullname can contain only letters and spaces'), 131);
		}
		if (strlen($data['fullname']) > app::app()->params['fullname_len_max']) {
			throw new Exception(app::t('Fullname cannot be longer than {num} characters', 'messages', ['{num}'=>app::app()->params['fullname_len_max']]), 132);
		}
		//sex
		$data['sex'] = strtolower($data['sex']);
		if (!in_array($data['sex'], ['male','female'])) {
			$data['sex'] = 'male';
		}

		$s = Model::getPdo()->prepare(<<<TXT
INSERT INTO users
	(user_id, username, password, email, fullname, sex, created_date, updated_date, name, surname)
VALUES 
	(UUID(), :username, :password, :email, :fullname, :sex, NOW(), NOW(), :name, :surname)
TXT
);
		foreach ($data as $k => $v) {
			if ($k == 'password') {
				$v = self::password_salted($v);
			}
			$s->bindValue(":$k", $v);
		}
		// extra splitter
		$s->bindValue(':name', substr($data['fullname'], 0, strpos($data['fullname'], ' ')));//
		$s->bindValue(':surname', substr($data['fullname'], strpos($data['fullname'], ' ')+1));
		return $s->execute();
	}
	
	
	
	public function saveAvatar($file)
	{
		if (empty($this->user_id)) {
			throw new Exception(app::t('Unauthorized request'), 140);
		}
		
		if (empty($file) || !is_array($file) || !is_readable($file["tmp_name"])) {
			throw new Exception(app::t('Avatar cannot be empty'), 141);
		}

		$mime = exif_imagetype($file["tmp_name"]);					
		
		if (!in_array($mime, app::app()->params['avatar_allowed_mime'])  
			|| $file["size"] > app::app()->params['avatar_max_size']*1000) {
			throw new Exception(app::t('Max filesize is {kb}kb, types [{types}]', 'messages',
			 [
			 	'{kb}' => app::app()->params['avatar_max_size'],
			 	'{types}' => implode(',',array_keys(app::app()->params['avatar_allowed_mime']))]), 142);
		}
		
		move_uploaded_file($file['tmp_name'], app::app()->params['avatar_path'] . $this->user_id .'.jpg');
	}

}