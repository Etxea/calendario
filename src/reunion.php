<?php
/*
 * Vista general, solo muestra la plantilla sin datos. Los datos se cargan de los JSON de cada mes
 */
$reunion = $app['controllers_factory'];
$reunion->get('/', function () use ($app) {
    return $app['twig']->render('reunion.html',array('ano'=>2014));
})
->bind('reunion')
;

return $reunion;

?>
