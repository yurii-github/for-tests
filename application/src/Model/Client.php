<?php
namespace App\Model;

class Client {
    //TODO: security checks

    public $id;
    public $firstName;
    public $lastName;
    public $sex;
    /**
     * @var \DateTime
     */
    public $birthDate;

    const SEX_FEMALE = 'female';
    const SEX_MALE = 'male';

    /**
     * 1toN
     * @var Account[]
     */
    public $accounts;
    /**
     * 1toN
     * @var Deposit[]
     */
    public $deposits;

    public function setId($id) {

    }

    public function setFirstName($firstName) {

        if(strlen($firstName) < 1) {
            throw new \Exception('firstname is too short');
        }

        $this->firstName = $firstName;
    }

    //TODO: add other checks etc
}