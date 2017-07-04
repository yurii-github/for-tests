<?php
namespace App\Controller;

use App\Model\Client;
use App\Repository\ClientRepository;

class DefaultController extends BaseController {

	public function action_index()
	{
	    $client = new Client();
	    $client->setFirstName('AAAA');
	    $client->lastName = 'BBBBB';
	    $client->sex = Client::SEX_MALE;
	    $client->birthDate = \DateTime::createFromFormat('Y-m-d', '2000-01-01');

        $repo = new ClientRepository();
	    $repo->insert($client);

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
