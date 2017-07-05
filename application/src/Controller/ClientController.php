<?php

namespace App\Controller;

use App\Model\Client;
use App\Repository\ClientRepository;


class ClientController extends BaseController
{
    public function action_generate_clients()
    {
        $names = ['Alex', 'Zend', 'Test'];
        $lastnames = ['LastAlex', 'LastZend', 'LastTest'];

        $client = new Client();
        $client->setFirstName($names[rand(0, 2)]);
        $client->lastName = $lastnames[rand(0, 2)];
        $client->sex = rand(0, 1) ? Client::SEX_MALE : Client::SEX_FEMALE;
        $client->birthDate = \DateTime::createFromFormat('Y-m-d', 1970 + rand(-30, 2017 - 1970 - 18) . '-' . rand(1, 12) . '-' . rand(1, 31));

        (new ClientRepository())->insert($client);

        \HTTP::redirect(\Route::get('clients')->uri());
    }


    public function action_clients()
    {
        $this->template->set('content', \View::factory('default/clients', [
            'clients' => (new ClientRepository())->findAll()
        ]));
    }


}