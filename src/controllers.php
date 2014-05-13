<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

//Mostramos un HTML que cargará vía AJAX la tabla /ver controlador ocupación)

$app->get('/', function () use ($app) {
    $ano= date("Y");
    $semana_obj = $app['calendr']->getWeek($ano,1);
    $inicio_semana=$semana_obj->getBegin();
    return $app['twig']->render('index.html', 
        array('ano'=>$inicio_semana->format('Y'),'mes'=>$inicio_semana->format('m'),'dia'=>$inicio_semana->format('d')));
})
->bind('homepage')
;

$app->get('/{ano}/{semana}/', function ($ano,$semana) use ($app) {
    $semana_obj = $app['calendr']->getWeek($ano,$semana);
    $inicio_semana=$semana_obj->getBegin();
    return $app['twig']->render('index.html',
        array('ano'=>$inicio_semana->format('Y'),'mes'=>$inicio_semana->format('m'),'dia'=>$inicio_semana->format('d')));
    
})
;



//Mostramos un HTML que cargará vía AJAX la tabla /ver controlador ocupación)
$app->get('/programacion-servicio/{ano}/{semana}/', function ($ano,$semana) use ($app) {
    $semana_obj = $app['calendr']->getWeek($ano,$semana);
    $inicio_semana=$semana_obj->getBegin();
    return $app['twig']->render('programacion-servicio.html', 
        array('ano'=>$inicio_semana->format('Y'),'mes'=>$inicio_semana->format('m'),'dia'=>$inicio_semana->format('d')));
})
->assert('ano', '\d+') //nos aseguramos que nos pasan un decimal
->assert('semana', '\d+') //nos aseguramos que nos pasan un decimal
->bind('programacion-servicio-ano-mes')
;


$app->get('/programacion-servicio/', function () use ($app) {
    $ano= date("Y");
    $semana_obj = $app['calendr']->getWeek($ano,1);
    $inicio_semana=$semana_obj->getBegin();
    #var_dump($semana_obj);
    #var_dump($inicio_semana);
    return $app['twig']->render('programacion-servicio.html', 
        array('ano'=>$inicio_semana->format('Y'),'mes'=>$inicio_semana->format('m'),'dia'=>$inicio_semana->format('d')));
})
->assert('ano', '\d+') //nos aseguramos que nos pasan un decimal
->assert('semana', '\d+') //nos aseguramos que nos pasan un decimal
->bind('programacion-servicio')
;


//SubControladores para organizar el código
$app->mount('/ocupacion', include 'ocupacion.php');
$app->mount('/servicios', include 'servicios.php');
$app->mount('/usuarios', include 'usuarios.php');

//montamos el controlador de las vacaciones
$app->mount('/vacaciones', include 'vacaciones.php');
$app->mount('/graciables', include 'graciables.php');
$app->mount('/baja-laboral', include 'baja-laboral.php');
$app->mount('/congresos', include 'congresos.php');
$app->mount('/investigacion', include 'investigacion.php');
$app->mount('/reunion', include 'reunion.php');

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
