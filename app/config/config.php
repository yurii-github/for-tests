<?php

$cfg = [
	'basePath' => dirname(__DIR__), //application dir
	'error_view' => dirname(__DIR__) . '/views/error.php', // $error is sent as Exception object TODO: check if not set etc...
	'request' => [
		'namespace' => 'app\controllers',
		'defaultController' => 'profile',
		'defaultAction' => 'index'
	],
	'db' => [
		'dsn' => 'mysql:host=localhost;dbname=DBNAME',
		'username' => 'USERNAME',
		'password' => 'PASSWORD'
	],
	'user' => 'app\models\User', // used for authentication, must implement framework\IUser
	
	'params' => [
		'username_len_min' => 3,
		'username_len_max' => 20,
		'password_len_min' => 3,
		'password_len_max' => 16,
		'fullname_len_max' => 255,
		'avatar_max_size' => 2000, //in kb
		'avatar_allowed_mime' => ['jpg' => IMAGETYPE_JPEG, 'png' => IMAGETYPE_PNG, 'gif' => IMAGETYPE_GIF],
		'avatar_path' => dirname(__DIR__) . '/public/avatars/',
		'avatar_web_path' => 'avatars/',
		
		
	]
];


if (file_exists(__DIR__.'/config.local.php')) {
  $cfg = (include __DIR__.'/config.local.php') + $cfg;
}

return $cfg;