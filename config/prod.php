<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
	 'host'      => 'localhost',
         'dbname'    => 'uroges',
         'user'      => 'uroges',
         'password'  => 'uroges',
         'charset'   => 'utf8',
    ),
));
