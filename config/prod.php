<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
	 'host'      => 'mysql_read.someplace.tld',
         'dbname'    => 'my_database',
         'user'      => 'my_username',
         'password'  => 'my_password',
         'charset'   => 'utf8',
    ),
));
