<?php

if (file_exists(__DIR__.'/database.local.php')) {
    return include __DIR__.'/database.local.php';
}


return array
(
    'default' => array
    (
        'type'       => 'PDO',
        'connection' => array(
            /**
             * The following options are available for PDO:
             *
             * string   dsn         Data Source Name
             * string   username    database username
             * string   password    database password
             * boolean  persistent  use persistent connections?
             */
            'dsn'        => 'mysql:host=localhost;dbname=tstech_test',
            'username'   => 'root',
            'password'   => 'YOURPASS',
            'persistent' => FALSE,
        ),
        /**
         * The following extra options are available for PDO:
         *
         * string   identifier  set the escaping identifier
         */
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),
);

