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

return $app;
