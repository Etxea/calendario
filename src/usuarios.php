<?php

$usuarios = $app['controllers_factory'];
$usuarios->get('/', function () use ($app) {
    return $app['twig']->render('usuarios.html', array());
})
->bind('usuarios')
;

$usuarios->match('/alta', function () use ($app) {
    if ($app['request']->getMethod() == "POST" ) {
        echo "POST";
        $mensaje = "Vamos a dar de alta";
    } else {
        $mensaje = "Introduce los datos";
    }
    return $app['twig']->render('usuarios-alta.html', array('mensaje' => $mensaje ));
})
->bind('usuarios-alta')
;

$usuarios->match('/editar/{id}', function ($id) use ($app) {
    if ($app['request']->getMethod() == "POST" ) {
        $mensaje = "Vamos guardar";
    } else {
        $mensaje = "vamos a editar";
    }
    return $app['twig']->render('usuarios-editar.html', array('id_usuario'=> $id ,'mensaje'=>$mensaje));
})
->bind('usuarios-editar')
->assert('id', '\d+') //nos aseguramos que nos pasan un decimal
;

return $usuarios

?>
