<?php

$usuarios = $app['controllers_factory'];
$usuarios->get('/', function () use ($app) {
    return $app['twig']->render('usuarios.html', array());
})
->bind('usuarios')
;

$usuarios->get('/alta', function () use ($app) {
    return $app['twig']->render('usuarios-alta.html', array());
})
->bind('usuarios-alta')
;

$usuarios->get('/editar/{id}', function ($id) use ($app) {
    return $app['twig']->render('usuarios-alta.html', array());
})
->bind('usuarios-editar')
;

return $usuarios

?>
