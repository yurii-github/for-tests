<?php

namespace App\Model;

class Deposit
{
    //TODO: security checks

    public $id;

    /**
     * @var \DateTime
     */
    public $openDate;

    /**
     * 1to1
     * @var Account
     */
    public $account;

    /**
     * Nto1
     * @var Client
     */
    public $client;

    public $name;

    public $balance;

    public $depositPercent;
}