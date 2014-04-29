<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$app->get('/programacion-servicio', function () use ($app) {
    return $app['twig']->render('programacion-servicio.html', array());
})
->bind('programacion-servicio')
;

$app->mount('/vacaciones', include 'vacaciones.php');

$app->get('graciables', function () use ($app) {
    return $app['twig']->render('graciables.html', array());
})
->bind('graciables')
;

$app->get('baja-laboral', function () use ($app) {
    return $app['twig']->render('baja-laboral.html', array());
})
->bind('baja-laboral')
;

$app->get('congresos', function () use ($app) {
    return $app['twig']->render('congresos.html', array());
})
->bind('congresos')
;

$app->get('investigacion', function () use ($app) {
    return $app['twig']->render('investigacion.html', array());
})
->bind('investigacion')
;

$app->get('reunion', function () use ($app) {
    return $app['twig']->render('reunion.html', array());
})
->bind('reunion')
;

$app->mount('/usuarios', include 'usuarios.php');


$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
