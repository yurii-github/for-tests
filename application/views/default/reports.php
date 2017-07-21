
<h1>Income by Month</h1>
<table class="table">
    <thead>
    <tr>
        <th>yearmonth</th>
        <th>income</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($yearmonth as $ym): ?>
        <tr>
            <td><?= $ym->yearmonth;?></td>
            <td><?= $ym->income; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h1>Average Deposits By Group</h1>
<table class="table">
    <thead>
    <tr>
        <th>count</th>
        <th>average balance</th>
        <th>Group</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($avgByGroup as $avg): ?>
        <tr>
            <td><?= $avg->dep_count;?></td>
            <td><?= $avg->dep_avg_balance; ?></td>
            <td><?= $avg->dep_group; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

