<?php
namespace App\Controller;

class Welcome extends \Controller {

	public function action_index()
	{
		$this->response->body('hello, world2222!');
	}

} // End Welcome
