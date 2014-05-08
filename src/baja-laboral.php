<?php
/*
 * Vista general, solo muestra la plantilla sin datos. Los datos se cargan de los JSON de cada mes
 */
$bajalaboral = $app['controllers_factory'];
$bajalaboral->get('/', function () use ($app) {
    return $app['twig']->render('baja-laboral.html',array('ano'=>2014));
})
->bind('baja-laboral')
;

return $bajalaboral;

?>
