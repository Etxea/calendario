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

/*
 * Devolvemos un JSON con un array que tiene por cada usuario y cada dia del mes un 1 o un 0 indicando si tiene fiesta o no
 */
$vacaciones->get('/{ano}/{mes}/', function ($ano,$mes) use ($app) {
    $lista_vacaciones = array();
    //Usamos la librería calendr para sacar los días del mes y año
    $calendario_mes = $app['calendr']->getMonth($ano, $mes);
    //var_dump($calendario_mes);
    $lista_usuarios = $app['db']->fetchAll('SELECT * FROM usuarios');
    foreach($lista_usuarios as  $usuario) {
        //echo "Usuario: ".$usuario['username'];
        $lista_vacaciones[$usuario['username']]['username'] = $usuario['username'];
        $lista_vacaciones[$usuario['username']]['id'] = $usuario['id'];
        $lista_vacaciones[$usuario['username']]['vacaciones'] = array();
        //recorremos las semanas del mes
        foreach($calendario_mes->getDays()  as $dia) {
            //echo "Día ".$dia->format("d")."\n";
            //rellenamos los días
            //esto hay que leerlo de BBDD
            $lista_vacaciones[$usuario['username']]['vacaciones'][$dia->format("Y-m-d")] = array(
                "user_id" => $usuario['username']['id'],
                "activo" => 0
            );

        }
    }
    return $app->json($lista_vacaciones);
})
->bind('vacaciones-ano-mes')
->assert('ano', '\d+') //nos aseguramos que nos pasan un decimal
->assert('mes', '\d+') //nos aseguramos que nos pasan un decimal
;

/*
 * Devolvemos un HTML con un array que tiene por cada usuario y cada dia del mes un 1 o un 0 indicando si tiene fiesta o no
 */
$vacaciones->get('/{ano}/{mes}/html/', function ($ano,$mes) use ($app) {
    $lista_vacaciones = array();
    //Usamos la librería calendr para sacar los días del mes y año
    $calendario_mes = $app['calendr']->getMonth($ano, $mes);
    //var_dump($calendario_mes);
    $lista_usuarios = $app['db']->fetchAll('SELECT * FROM usuarios');
    //recorremos los días del mes
    foreach($calendario_mes->getDays()  as $dia) {
        $lista_vacaciones[$dia->format("Y-m-d")]['dia_mes'] = $dia->format("d");
        $lista_vacaciones[$dia->format("Y-m-d")]['vacaciones'] = array();
        foreach($lista_usuarios as  $usuario) {
            $lista_vacaciones[$dia->format("Y-m-d")]['vacaciones'][$usuario['username']]= array(
                "dia" => $dia->format("Y-m-d"),
                "user_id" => $usuario['id'],
                "activo" => rand(0,1)
                );
        }
    };
    //var_dump($lista_vacaciones);
    return $app['twig']->render('vacaciones-tabla.html',array('ano'=>$ano,'mes'=> $mes,'lista_usuarios'=>$lista_usuarios,'lista_vacaciones'=>$lista_vacaciones));
})
->bind('vacaciones-ano-mes-html')
->assert('ano', '\d+') //nos aseguramos que nos pasan un decimal
->assert('mes', '\d+') //nos aseguramos que nos pasan un decimal
;


/*
 * Añadimos un día de vacaciones.
 */
$vacaciones->match('/add/{id}/{fecha}/', function ($id,$fecha) use ($app) {
    return $app->json(array("estado"=> "ok", 
        "mensaje"=> "Agregado dia de vacaciones el ".$fecha." Al usuario".$id));
})
->bind('vacaciones-add')
->assert('id', '\d+') //nos aseguramos que nos pasan un decimal
;

/*
 * Eliminamos un día de vacaciones.
 */
$vacaciones->match('/del/{id}/{fecha}/', function ($id,$fecha) use ($app) {
    return $app->json(array("estado"=> "ok", 
        "mensaje"=> "Eliminado dia de vacaciones el ".$fecha." Al usuario ".$id));
})
->bind('vacaciones-del')
->assert('id', '\d+') //nos aseguramos que nos pasan un decimal
;

return $vacaciones;

?>
