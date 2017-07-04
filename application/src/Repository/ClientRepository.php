<?php
namespace App\Repository;

/*
 * NOTE: LIMITED IMPLEMENTATION!!! HARDCODED!!! NOT SOLID!!!
 *
 * but OK for test task!
 */

use App\Model\Client;

class ClientRepository {

    public function insert(Client $c) {
        \DB::insert('client',
                ['firstname', 'lastname', 'sex' , 'birthdate'])
            ->values(
                [$c->firstName, $c->lastName, $c->sex, $c->birthDate->format('Y-m-d')])
            ->execute();
    }
}