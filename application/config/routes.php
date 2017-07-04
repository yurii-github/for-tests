<?php
/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

/**************************************************************
 * Default Router
 **************************************************************/
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

