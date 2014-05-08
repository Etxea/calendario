<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

//Mostramos un HTML que cargará vía AJAX la tabla /ver controlador ocupación)
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;




//Mostramos un HTML que cargará vía AJAX la tabla /ver controlador ocupación)
$app->get('/programacion-servicio', function () use ($app) {
    return $app['twig']->render('programacion-servicio.html', array());
})
->bind('programacion-servicio')
;
//montamos el controlador de las vacaciones
$app->mount('/vacaciones', include 'vacaciones.php');
$app->mount('/graciables', include 'graciables.php');
$app->mount('/baja-laboral', include 'baja-laboral.php');
$app->mount('/congresos', include 'congresos.php');
$app->mount('/investigacion', include 'investigacion.php');
$app->mount('/reunion', include 'reunion.php');

//SUBControladores para organizar el código
$app->mount('/ocupacion', include 'ocupacion.php');
$app->mount('/servicios', include 'servicios.php');
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
