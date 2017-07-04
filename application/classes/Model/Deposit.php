<?php
namespace App\Model;

class Deposit {
    public $id;

    /**
     * 1to1
     * @var Account
     */
    public $account;

    public $balance;

    public $depositPercent;
}