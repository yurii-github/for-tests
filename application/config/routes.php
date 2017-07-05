<?php

Route::set('reports', 'reports')
    ->defaults(array(
        'controller' => '\App\Controller\DepositController',
        'action'     => 'reports',
    ));

Route::set('cron', 'cron')
    ->defaults(array(
        'controller' => '\App\Controller\CronController',
        'action'     => 'index',
    ));

Route::set('cron_run', 'cron-run')
    ->defaults(array(
        'controller' => '\App\Controller\CronController',
        'action'     => 'run',
    ));

Route::set('clients', 'clients')
    ->defaults(array(
        'controller' => '\App\Controller\ClientController',
        'action'     => 'clients',
    ));

Route::set('generate_clients', 'generate-clients')
    ->defaults(array(
        'controller' => '\App\Controller\ClientController',
        'action'     => 'generate_clients',
    ));

Route::set('deposits', 'deposits')
    ->defaults(array(
        'controller' => '\App\Controller\DepositController',
        'action'     => 'deposits',
    ));

Route::set('add_deposit', 'add-deposit')
    ->defaults(array(
        'controller' => '\App\Controller\DepositController',
        'action'     => 'addDeposit',
    ));


Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => '\App\Controller\DefaultController',
        'action'     => 'index',
    ));

