<?php
namespace App\Model;

class Account {
    public $id;
    public $balance;

    /**
     * 1to1
     * @var Deposit
     */
    public $deposit;
}