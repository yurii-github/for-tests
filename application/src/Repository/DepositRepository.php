<?php

namespace App\Repository;

/*
 * NOTE: LIMITED IMPLEMENTATION!!! HARDCODED!!! NOT SOLID!!!
 *
 * but OK for test task!
 */

use App\Model\Client;
use App\Model\Deposit;

class DepositRepository
{
    //TODO: move reports to another place

    // 1. Убыток или прибыль банка по месяцам. (Сумма комиссий - Сумма начисленных процентов)
    public function report_IncomeYearmonth()
    {
        $query = <<<SQL
SELECT EXTRACT(YEAR_MONTH FROM log_date) yearmonth, SUM(total) income FROM (
SELECT *, -SUM(amount) total FROM transaction_log
WHERE type = 'commission'
GROUP BY YEAR(log_date), MONTH(log_date)

UNION

SELECT *, SUM(amount) total FROM transaction_log
WHERE type = 'payment'
GROUP BY YEAR(log_date), MONTH(log_date)

) t
GROUP BY YEAR(log_date), MONTH(log_date)
;
SQL;
        return \DB::query(\Database::SELECT, $query)->execute(null, true);
    }

    /*
    // 2. Средняя сумма депозита (Сумма депозитов/Количество депозитов) для возрастных групп:
1. I группа - От 18 до 25 лет
2. II группа - От 25 до 50 лет
3. III группа - От 50 лет
    */
    public function report_AverageByGroup()
    {
        $query = <<<SQL
SELECT * FROM (
SELECT COUNT(*) AS dep_count, 
AVG(balance) as dep_avg_balance,

IF(
age >=18 AND age < 25, 'I',
 IF(age >= 25 AND age < 50, 'II', 
  IF(age >= 50, 'III', 'KID'
 ))) as dep_group 
FROM (
 SELECT  d.*, 
 c.id c_id, TIMESTAMPDIFF(YEAR, c.birthdate, CURDATE()) AS age, 
 c.firstname as c_firstname, c.lastname as c_lastname  FROM deposit d
 JOIN account_deposit ad ON ad.deposit_id = d.id
 JOIN account a ON a.id = ad.account_id
 JOIN client c ON c.id = a.client_id) t
 
  GROUP BY dep_group
 ) t2 WHERE t2.dep_group != 'KID';
SQL;

        return \DB::query(\Database::SELECT, $query)->execute(null, true);
    }


    public function update(Deposit $depost)
    {
        $result = \DB::update('deposit')->set(['balance' => $depost->balance])->where('id', '=', $depost->id)->execute();

        if ($result !== 1) {
            throw new \Exception('FAIL');
        }
    }

    public function insert(Deposit $deposit)
    {
        $result = \DB::insert('deposit',
            ['name', 'balance', 'deposit_percent', 'open_date'])
            ->values(
                [$deposit->name, $deposit->balance, $deposit->depositPercent, $deposit->openDate->format('Y-m-d')])
            ->execute();

        if ($result[1] != 1) {
            throw new \Exception('FAIL');
        }

        //1to1
        $deposit->id = $result[0];
        \DB::insert('account_deposit', ['deposit_id', 'account_id'])->values([$deposit->id, $deposit->account->id])
            ->execute();
    }


    public function findAll()
    {
        /** @var \Database_Result_Cached $cache */

        /*
         * SELECT d.*, c.firstname, c.lastname from deposit d
JOIN account_deposit ad ON d.id = ad.deposit_id
JOIN account a ON ad.account_id = a.id
JOIN client c ON a.client_id = c.id;
         */
        $cache = \DB::select('d.*, c.firstname as c_firstname, c.lastname as c_lastname')->from(['deposit', 'd'])
            ->join(['account_deposit', 'ad'])->on('d.id', '=', 'ad.deposit_id')
            ->join(['account', 'a'])->on('ad.account_id', '=', 'a.id')
            ->join(['client', 'c'])->on('a.client_id', '=', 'c.id')
            ->execute();

        $data = [];
        foreach ($cache as $row) {
            $client = new Client();
            //?TODO: FULL OBJECT LOAD

            $client->firstName = $row['c_firstname'];
            $client->lastName = $row['c_lastname'];

            $c = new Deposit();
            $c->id = $row['id'];
            $c->depositPercent = $row['deposit_percent'];
            $c->balance = $row['balance'];
            $c->openDate = \DateTime::createFromFormat('Y-m-d H:i:s', $row['open_date']);
            $c->name = $row['name'];
            $c->client = $client;

            $data[$row['id']] = $c;
        }

        return $data;
    }


    public function findToPayLimit100($date)
    {
        /*
         * Проценты начисляются каждый месяц. Если депозит был сделан 17 апреля, проценты начисляются каждый месяц 17-го числа.
         *  Если депозит был сделан 31 числа, проценты начисляются в последний день месяца.
         * set @d = '2017-07-01';

 SELECT  d.*, tl.id tl_id  FROM tstech_test.deposit d
LEFT JOIN transaction_log tl
ON d.id = tl.deposit_id AND CAST(@d AS DATE) = CAST(tl.log_date AS DATE)

WHERE
-- not payed
tl.id IS NULL AND
-- at least 1 month passed
d.open_date <= DATE_SUB(@d, INTERVAL 1 MONTH)
AND
-- is it last day?
IF(EXTRACT(DAY FROM d.open_date) > EXTRACT(DAY FROM @d) AND EXTRACT(DAY FROM @d) = LAST_DAY(@d),
-- its last day, pay
1,
-- else check day match
EXTRACT(DAY FROM d.open_date) = EXTRACT(DAY FROM @d)
)
GROUP BY d.id;
         */
        $query = <<<SQL
 SELECT  d.*, tl.id tl_id, c.id c_id, c.firstname as c_firstname, c.lastname as c_lastname  FROM deposit d
 JOIN account_deposit ad ON ad.deposit_id = d.id
 JOIN account a ON a.id = ad.account_id
 JOIN client c ON c.id = a.client_id
LEFT JOIN transaction_log tl 
ON d.id = tl.deposit_id AND tl.type = 'payment' AND CAST(@d AS DATE) = CAST(tl.log_date AS DATE)
	
WHERE 
-- not payed
tl.id IS NULL AND
-- at least 1 month passed
d.open_date <= DATE_SUB(@d, INTERVAL 1 MONTH)
AND
-- is it last day?
IF(EXTRACT(DAY FROM d.open_date) > EXTRACT(DAY FROM @d) AND EXTRACT(DAY FROM @d) = LAST_DAY(@d),
-- its last day, pay
1,
-- else check day match
EXTRACT(DAY FROM d.open_date) = EXTRACT(DAY FROM @d)
)
GROUP BY d.id
LIMIT 100
SQL;

        $cache = \DB::query(\Database::SELECT, str_replace('@d', '\'' . $date->format('Y-m-d') . '\'', $query))->execute()->as_array('id');

        $data = [];
        foreach ($cache as $row) {
            $client = new Client();
            //TODO: FULL OBJECT LOAD
            $client->id = $row['c_id'];
            $client->firstName = $row['c_firstname'];
            $client->lastName = $row['c_lastname'];

            $c = new Deposit();
            $c->id = $row['id'];
            $c->depositPercent = $row['deposit_percent'];
            $c->balance = $row['balance'];
            $c->openDate = \DateTime::createFromFormat('Y-m-d H:i:s', $row['open_date']);
            $c->name = $row['name'];
            $c->client = $client;

            $data[$row['id']] = $c;
        }

        return $data;
    }


    public function findToCommission100($date)
    {

        $query = <<<SQL
 SELECT  d.*, tl.id tl_id, c.id c_id, c.firstname as c_firstname, c.lastname as c_lastname  FROM deposit d
 JOIN account_deposit ad ON ad.deposit_id = d.id
 JOIN account a ON a.id = ad.account_id
 JOIN client c ON c.id = a.client_id
LEFT JOIN transaction_log tl 
ON d.id = tl.deposit_id AND tl.type = 'commission' AND CAST(@d AS DATE) = CAST(tl.log_date AS DATE)
	
WHERE 
-- not commissioned
tl.id IS NULL
GROUP BY d.id
LIMIT 100;
SQL;

        $cache = \DB::query(\Database::SELECT, str_replace('@d', '\'' . $date->format('Y-m-d') . '\'', $query))->execute()->as_array('id');

        $data = [];
        foreach ($cache as $row) {
            $client = new Client();
            //TODO: FULL OBJECT LOAD
            $client->id = $row['c_id'];
            $client->firstName = $row['c_firstname'];
            $client->lastName = $row['c_lastname'];

            $c = new Deposit();
            $c->id = $row['id'];
            $c->depositPercent = $row['deposit_percent'];
            $c->balance = $row['balance'];
            $c->openDate = \DateTime::createFromFormat('Y-m-d H:i:s', $row['open_date']);
            $c->name = $row['name'];
            $c->client = $client;

            $data[$row['id']] = $c;
        }

        return $data;
    }

}
