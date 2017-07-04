<?php
namespace App\Controller;


class DefaultController extends BaseController {

	public function action_index()
	{
	    $this->template->set('content', \View::factory('default/index'));
	}

}