<?php

$vacaciones = $app['controllers_factory'];
$vacaciones->get('/', function () use ($app) {
    return $app['twig']->render('vacaciones.html');
})
->bind('vacaciones')
;

/*
 * Devolvemos un JSON con un array que tiene por cada usuario y cada dia del mes un 1 o un 0 indicando si tiene fiesta o no
 */
$vacaciones->get('/{ano}/{mes}/', function () use ($app) {
    $lista_vacaciones = array();
    //Usamos la librería calendr para sacar los días del mes y año
    $dias_mes = 
    $lista_usuarios = $app['db']->fetchAll('SELECT * FROM usuarios');
    foreach($lista_usuarios as  $usuario) {
        $lista_vacaciones[$usuario['username']]['id'] = $usuario['id'];
        
        $lista_vacaciones[$usuario['username']]['vacaciones'] = array(1=>0,2=>0,3=>0,4=>0,5=>1,6=>0,7=>0,8=>0,9=>0,10=>1);
    }
    return $app->json($lista_vacaciones);
})
->bind('vacaciones-ano-mes')
->assert('ano', '\d+') //nos aseguramos que nos pasan un decimal
->assert('mes', '\d+') //nos aseguramos que nos pasan un decimal
;


return $vacaciones

?>
