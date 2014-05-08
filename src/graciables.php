<?php
/*
 * Vista general, solo muestra la plantilla sin datos. Los datos se cargan de los JSON de cada mes
 */
$graciables = $app['controllers_factory'];
$graciables->get('/', function () use ($app) {
    return $app['twig']->render('graciables.html',array('ano'=>2014));
})
->bind('graciables')
;

return $graciables;

?>
