<?php
/** @var \App\Model\Client[] $clients */
?>

<h1>Clients</h1>
<table class="table">
    <thead>
    <tr>
        <th>id</th>
        <th>1st name</th>
        <th>last name</th>
        <th>sex</th>
        <th>date</th>
        <th>age</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($clients as $client): ?>
        <tr>
            <td><?= $client->id;?></td>
            <td><?= $client->firstName;?></td>
            <td><?= $client->lastName;?></td>
            <td><?= $client->sex;?></td>
            <td><?= $client->birthDate->format('Y m d')?></td>
            <td><?= $client->birthDate->diff(new DateTime('now'))->y; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<form action="<?=url::base();?>generate-clients" method="post">
    <button id="generate-client" type="submit">generate</button>
</form>
