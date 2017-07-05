<?php
namespace App\Model;

class TransactionLog {
    //TODO: security checks

    public $id;
    public $amount;

    const TYPE_PAYMENT = 'payment';
    const TYPE_COMMISSION = 'commission';

    /**
     * @var \DateTime
     */
    public $logDate;


    public $depositBalanceBefore;
    /**
     * Nto1
     * @var Client
     */
    public $client;

    /**
     * Nto1
     * @var Deposit
     */
    public $deposit;

    /**
     * @var string payment|commission
     */
    public $type;

    public $percent;

}