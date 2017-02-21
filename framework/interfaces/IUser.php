<?php
namespace framework\interfaces;

/**
 * 
 * instance of this class corresponds to current user
 *
 */
interface IUser
{
	/**
	 * validate by session or other means
	 * @return IUser on success, containg user data, or false on failure
	 */
	public static function validate();

	/**
	 * 
	 * @param string $username
	 * @param string $password
	 * 
	 * @return IUser|false logged in IUser on success, containg user's data, or false on failure
	 */
	public static function login($username, $password);
	
	/**
	 * remove active for current IUser instance
	 */
	public function logout();
	
	
}