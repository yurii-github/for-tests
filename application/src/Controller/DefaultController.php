<?php
namespace App\Controller;

use App\ORM;

class DefaultController extends BaseController {

	public function action_index()
	{
	    $this->template->set('content', \View::factory('default/index'));

	    //$this->template->content = 'zzzzzzz';
	    //$view = \View::factory('asd');
	    //$this->request->response = $view;
/*
            return;
	    $client = ORM::factory('\App\Model\Client',1);
	    var_dump($client);*/
		//$this->response->body('hello, world2222!');
	}

} // End Welcome
