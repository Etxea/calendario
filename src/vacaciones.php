<?php
/*
 * Vista general, solo muestra la plantilla sin datos. Los datos se cargan de los JSON de cada mes
 */
$vacaciones = $app['controllers_factory'];
$vacaciones->get('/', function () use ($app) {
    return $app['twig']->render('vacaciones.html',array('ano'=>2014));
})
->bind('vacaciones')
;

return $vacaciones;

?>
