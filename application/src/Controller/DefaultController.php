<?php
namespace App\Controller;

use App\Model\Account;
use App\Model\Client;
use App\Model\Deposit;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use App\Repository\DepositRepository;
use App\SOA\DepositManager;

class DefaultController extends BaseController {

    protected function addClients() {
        $names = ['Alex', 'Zend'];

        $client = new Client();
        $client->setFirstName('AAAA');
        $client->lastName = 'BBBBB';
        $client->sex = Client::SEX_MALE;
        $client->birthDate = \DateTime::createFromFormat('Y-m-d', '2000-01-01');

        $repo = new ClientRepository();

        foreach ($names as $name) {
            $_c = clone $client;
            $_c->setFirstName($name);
            $repo->insert($_c);
        }

        $this->template->set('content', \View::factory('default/index'));
    }

    protected function addDeposit($client) {


    }





	public function action_index()
	{
	    // addClients()

        /* add deposit

*/



	    $this->template->set('content', \View::factory('default/index'));
	}

} // End Welcome
