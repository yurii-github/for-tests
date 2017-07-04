<?php
namespace App\Model;

class Client {
    public $id;
    public $firstName;
    public $lastName;
    public $sex;
    public $birthDate;

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
}