<?php
/*
 * Vista general, solo muestra la plantilla sin datos. Los datos se cargan de los JSON de cada mes
 */
$investigacion = $app['controllers_factory'];
$investigacion->get('/', function () use ($app) {
    return $app['twig']->render('investigacion.html',array('ano'=>2014));
})
->bind('investigacion')
;

return $investigacion;

?>
