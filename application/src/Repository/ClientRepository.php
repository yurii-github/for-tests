<?php

namespace App\Repository;

/*
 * NOTE: LIMITED IMPLEMENTATION!!! HARDCODED!!! NOT SOLID!!!
 *
 * but OK for test task!
 */

use App\Model\Client;

class ClientRepository
{

    public function insert(Client $c)
    {
        \DB::insert('client',
            ['firstname', 'lastname', 'sex', 'birthdate'])
            ->values(
                [$c->firstName, $c->lastName, $c->sex, $c->birthDate->format('Y-m-d')])
            ->execute();
    }

    public function findAll()
    {
        /** @var \Database_Result_Cached $cache */
        $cache = \DB::select('*')->from('client')->execute();

        $data = [];
        foreach ($cache as $row) {
            $c = new Client();
            $c->id = $row['id'];
            $c->sex = $row['sex'];
            $c->firstName = $row['firstname'];
            $c->lastName = $row['lastname'];
            $c->birthDate = \DateTime::createFromFormat('Y-m-d H:i:s', $row['birthdate']);

            $data[$row['id']] = $c;
        }

        return $data;
    }

    public function find($id)
    {
        /** @var \Database_Result_Cached $cache */
        $cache = \DB::select('*')->from('client')->where('id', '=', $id)->limit(1)->execute(null, '\App\Model\Client');
        return $cache[0];
    }
}