<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

use Silex\Provider\ServiceControllerServiceProvider;
use Doctrine\DBAL\Connection;
use Silex\Provider\FormServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
}));

$app->register(new FormServiceProvider());

$app['calendr'] = new CalendR\Calendar;

/*
 * Seguridad. Definimos el Proveedor y un firewall que proteja los accesos.
 * http://silex.sensiolabs.org/doc/providers/security.html
 * http://www.bubblecode.net/en/2012/08/28/mysql-authentication-in-silex-the-php-micro-framework/
 */

/*

$app->register(new Silex\Provider\SecurityServiceProvider());


$app['security.firewalls'] = array(
    'login' => array(
        'pattern' => '^/login$',
    ),
    'secured' => array(
        'pattern' => '^.*$',
        'http' => true,
        //'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
        'users' => array(
            'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
        ),
    ),
);
*/

return $app;
