<?php
/*
 * Vista general, solo muestra la plantilla sin datos. Los datos se cargan de los JSON de cada mes
 */
$congresos = $app['controllers_factory'];
$congresos->get('/', function () use ($app) {
    return $app['twig']->render('congresos.html',array('ano'=>2014));
})
->bind('congresos')
;

return $congresos;

?>
