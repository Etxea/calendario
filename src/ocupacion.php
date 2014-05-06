<?php
/*
 * Vista general, solo muestra la plantilla sin datos. Los datos se cargan de los JSON de cada mes
 */
$ocupacion = $app['controllers_factory'];

/*
 * Devolvemos un HTML con un array que tiene por cada usuario y cada dia del mes un 1 o un 0 indicando si tiene fiesta o no
 */
$ocupacion->get('/{ano}/{mes}/{dia}/', function ($ano,$mes,$dia) use ($app) {
    //Usamos la librería calendr para sacar los días del la semana
    $weeknummer = date("W", mktime(0, 0, 1, $mes, $dia, $ano));
    //echo "Nums. semana ".$weeknummer;
    $lista_dias = $app['calendr']->getWeek($ano,$weeknummer);
    //var_dump($semana);
    $dia = $app['calendr']->getDay($ano,$mes,$dia);
    //var_dump($calendario_mes);
    $lista_usuarios = $app['db']->fetchAll('SELECT * FROM usuarios ORDER BY username ASC');
    $lista_servicios = $app['db']->fetchAll('SELECT servicios.id AS id ,servicios_tipo.nombre AS tipo, servicios.nombre AS nombre, estado, nombre_corto FROM servicios, servicios_tipo WHERE servicios.tipo = servicios_tipo.id ORDER BY servicios.id ASC');
    $lista_ocupacion = array();
    foreach($lista_servicios as $servicio){
        //echo "Buscando la ocupacio de cada usuario en el servicio ".$servicio['nombre_corto'];
        //$lista_ocupacion[$servicio['nombre_corto']] = array();
        foreach($lista_usuarios as $usuario) {
            //echo "Buscando la ocupacio del usuario ".$usuario['username']." en el servicio ".$servicio['nombre_corto'];
            $lista_ocupacion[$servicio['nombre_corto']][$usuario['username']] = array(
                "dia" => $dia->format("Y-m-d"),
                "user_id" => $usuario['id'],
                "servicio_id" => $servicio['id'],
                "activo" => rand(0,1)
                );
        }
    }
    //var_dump($lista_ocupacion);
    return $app['twig']->render('ocupacion-tabla.html',array('ano'=>$ano,'mes'=> $mes,'lista_usuarios'=>$lista_usuarios,'lista_servicios'=>$lista_servicios,'lista_ocupacion'=>$lista_ocupacion));
    
})
->bind('ocupacion-html')
->assert('ano', '\d+') //nos aseguramos que nos pasan un decimal
->assert('mes', '\d+') //nos aseguramos que nos pasan un decimal
->assert('dia', '\d+') //nos aseguramos que nos pasan un decimal
;


/*
 * Añadimos un día de vacaciones.
 */
$ocupacion->match('/add/{id_user}/{id_servicio}/{fecha}/', function ($id_user,$id_servicio,$fecha) use ($app) {
    return $app->json(array("estado"=> "ok", 
        "mensaje"=> "Agregado la ocupación el ".$fecha." al usuario".$id_user." en el servicio ".$id_servicio));
})
->bind('ocupacion-add')
->assert('id_user', '\d+') //nos aseguramos que nos pasan un decimal
->assert('id_servicio', '\d+') //nos aseguramos que nos pasan un decimal
;

/*
 * Eliminamos un día de ocupacion.
 */
$ocupacion->match('/del/{id_user}/{id_servicio}/{fecha}/', function ($id_user,$id_servicio,$fecha) use ($app) {
    return $app->json(array("estado"=> "ok", 
        "mensaje"=> "Eliminado la ocupacion del usuario ".$id_user." al servicio ".$id_servicio." el dia ".$fecha));
})
->bind('ocupacion-del')
->assert('id_user', '\d+') //nos aseguramos que nos pasan un decimal
->assert('id_servicio', '\d+') //nos aseguramos que nos pasan un decimal
;

return $ocupacion;

?>
