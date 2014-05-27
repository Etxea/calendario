<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;

use Silex\Provider\ServiceControllerServiceProvider;
use Doctrine\DBAL\Connection;
use Silex\Provider\FormServiceProvider;

use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Uroges\Usuarios;

// Set language to French
putenv('LC_ALL=es_ES');
setlocale(LC_ALL, 'es_ES');

// Specify the location of the translation tables
bindtextdomain('uroges', 'includes/locale');
bind_textdomain_codeset('uroges', 'UTF-8');

// Choose domain
textdomain('uroges');

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
    //$twig->addExtension(new Twig_Extensions_Extension_I18n());
    $filter = new Twig_SimpleFilter('traducir_dia_semana', function ($string) {
        $traducir = array( 'Monday'=>'Lunes',
                           'Tuesday' => 'Martes',
                           'Wednesday' => 'Miercoles',
                           'Thursday'=> 'Jueves',
                           'Friday'=> 'Viernes',
                           'Saturday'=> 'Sabado',
                           'Sunday'=> 'Domingo'
        );
        return $traducir["$string"];

    });
    $twig->addFilter($filter);
    return $twig;
}));

$app->register(new FormServiceProvider());

$app['calendr'] = new CalendR\Calendar;

/*
 * Seguridad. Definimos el Proveedor y un firewall que proteja los accesos.
 * http://silex.sensiolabs.org/doc/providers/security.html
 * http://www.bubblecode.net/en/2012/08/28/mysql-authentication-in-silex-the-php-micro-framework/
 */



$app->register(new Silex\Provider\SecurityServiceProvider());
$app->register(new SessionServiceProvider());

$app['security.firewalls'] = array(
    'login' => array(
        'pattern' => '^/login$',
    ),
    'secured' => array(
        'pattern' => '^.*$',
        'http' => true,
        'logout' => array('logout_path' => '/logout'),
        //'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
        'users' => array(
            // la contraseña es foo
            'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
        ),
        /*'users' => $app->share(function () use ($app) {
            return new Uroges\Usuarios\UrogesUserProvider($app['db']);
        }),*/
    ),
);


return $app;
