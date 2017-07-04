<?php
namespace App\Model;

class TransactionLog {
    public $id;
    public $amount;

    const TYPE_PAYMENT = 'payment';
    const TYPE_COMMISSION = 'commission';

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

    // only for commission type
    public $commissionPercent;

    // only for payment type
    public $paymentPercent;
}