<?php
/** @var \App\Model\Deposit[] $deposits */
?>

<h1>Deposits</h1>
<table class="table">
    <thead>
    <tr>
        <th>id</th>
        <th>client name</th>
        <th>deposit name</th>
        <th>balance</th>
        <th>percent</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($deposits as $deposit): ?>
        <tr>
            <td><?= $deposit->id;?></td>
            <td><?= ($deposit->client->firstName . ' ' . $deposit->client->lastName)?></td>
            <td><?= htmlentities($deposit->name); ?></td>
            <td><?= $deposit->balance;?></td>
            <td><?= $deposit->depositPercent?>%</td>
        </tr>
    <?php endforeach; ?>

    <form action="<?=url::base();?>add-deposit" method="post">
        <tr>

            <td><button id="add-deposit" type="submit">add new</button></td>
            <td>
            <select name="client_id">
                <?php foreach ($clients as $client): ?>
                    <option value="<?=$client->id?>">(<?=str_pad($client->id, 6, '0', STR_PAD_LEFT)?>) <?=htmlspecialchars($client->firstName);?> <?=htmlspecialchars($client->lastName);?></option>
                <?php endforeach; ?>
            </select>

            </td>
            <td><input name="deposit_name" /></td>
            <td><input name="deposit_balance" /></td>
            <td><input name="deposit_percent" type="number" />%</td>
        </tr>
    </form>




    </tbody>
</table>

