<?php
/*
 * Controlador de la parte de ocupacion y programacion de los servicios
 */
 
$ocupacion = $app['controllers_factory'];

//FIXME esto lod ebería hacer el autoload
include_once "../vendor/etxea/sabremw/lib/SabreMW/SabreMW.php";

/*
 * Devolvemos un HTML con la tabla de ocupacion usuario/servicio de un día. 
 * Esta tabla puede ser estatica (iconos) o dinámica (inputs tipo check). 
 * Con la dinamica llamamos vía AJAX a las funciones de añadir y borrar ocupación
 */
$ocupacion->get('/servicio/{ano}/{mes}/{dia}/{estatico}', function ($ano,$mes,$dia,$estatico) use ($app) {
    //Primero tenemos que calcular el umero de semana y el ano.
    //Es lioso porque puede pasar que el 31/12 de 2014 sea la semana 1 del año 2014
    $week_year = date("o-W", mktime(12, 0, 0, $mes, $dia, $ano));
    $week_year_array = explode("-",$week_year);
    $weeknumber = $week_year_array[1];
    $ano = $week_year_array[0];
    //echo "Nums. semana ".$weeknumber." el dia ".$dia." del mes ".$mes." del año ".$ano;
    //Usamos la librería calendr para sacar los días del la semana
    $lista_dias = $app['calendr']->getWeek($ano,$weeknumber);
    //var_dump($lista_dias);
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
            $ocupado = $app['db']->fetchAssoc('SELECT count(*) AS activo FROM ocupacion WHERE user_id = ? AND tipo_ocupacion = 1 AND tipo_servicio = ? AND fecha = ?',array($usuario['id'],$servicio['id'],$dia->format("Ymd")));
            $ocupado_otro = $app['db']->fetchAssoc('SELECT count(*) AS activo FROM ocupacion WHERE user_id = ? AND tipo_ocupacion = 2 AND tipo_servicio = ? AND fecha = ?',array($usuario['id'],$servicio['id'],$dia->format("Ymd")));
            //var_dump($ocupado);
            $lista_ocupacion[$servicio['nombre_corto']][$usuario['username']] = array(
                "dia" => $dia->format("Ymd"),
                "user_id" => $usuario['id'],
                "servicio_id" => $servicio['id'],
                "activo" => $ocupado['activo'],
                "otro_ocupado" => $ocupado_otro['activo']
                );
        }
    }
    //var_dump($lista_ocupacion);
    if ($estatico == 0) {
        return $app['twig']->render('ocupacion-tabla.html',array('ano'=>$ano,'mes'=> $mes,'lista_usuarios'=>$lista_usuarios,'lista_servicios'=>$lista_servicios,'lista_ocupacion'=>$lista_ocupacion,'semana'=>$lista_dias));
    }
    elseif ($estatico == 1) {
        return $app['twig']->render('ocupacion-tabla-estatica.html',array('ano'=>$ano,'mes'=> $mes,'lista_usuarios'=>$lista_usuarios,'lista_servicios'=>$lista_servicios,'lista_ocupacion'=>$lista_ocupacion,'semana'=>$lista_dias));
    }
})
->bind('ocupacion-servicio-html')
->assert('ano', '\d+') //nos aseguramos que nos pasan un decimal
->assert('mes', '\d+') //nos aseguramos que nos pasan un decimal
->assert('dia', '\d+') //nos aseguramos que nos pasan un decimal
->value('estatico', 0) //este parámetro es opcional y si está a 1 mostramos una tabla estatica
;

/*
 * Añadimos un día de ocupaciones.
 */
$ocupacion->match('/servicio/add/{id_user}/{id_servicio}/{fecha}/', function ($id_user,$id_servicio,$fecha) use ($app) {
    //Lo guardamos en el CalDAV
    $smw = new Etxea\SabreMW($app['db']);
    $user = $app['db']->fetchAssoc('SELECT * FROM usuarios WHERE id = ?',array($id_user));
    $servicio = $app['db']->fetchAssoc('SELECT * FROM servicios WHERE id = ?',array($id_servicio));
    $calendar_id = $smw->getUserCalendar($user['username']);
    $evento = $smw->addEvent($calendar_id,$servicio['nombre'],$servicio['nombre_corto'],$fecha);
    //Lo guardamos en BBDD
    $app['db']->insert('ocupacion',array('user_id'=>$id_user,'tipo_ocupacion'=>1,'tipo_servicio'=>$id_servicio,'fecha'=>$fecha,'caldav_id'=>$evento));
    return $app->json(array("estado"=> "ok", 
        "mensaje"=> "Agregado la ocupación el ".$fecha." al usuario".$id_user." en el servicio ".$id_servicio." con el ID en caldav ".$evento));
})
->bind('ocupacion-servicio-add')
->assert('id_user', '\d+') //nos aseguramos que nos pasan un decimal
->assert('id_servicio', '\d+') //nos aseguramos que nos pasan un decimal
;

/*
 * Eliminamos un día de ocupacion.
 */
$ocupacion->match('/servicio/del/{id_user}/{id_servicio}/{fecha}/', function ($id_user,$id_servicio,$fecha) use ($app) {
    $smw = new Etxea\SabreMW($app['db']);
    //Lo buscamos  en BBDD porque necesitamos el caldav_id
    $ocupacion = $app['db']->fetchAssoc('SELECT * FROM ocupacion WHERE user_id = ? AND tipo_ocupacion = 1 AND tipo_servicio = ? AND fecha = ?',array($id_user,$id_servicio,$fecha));
    //Lo borramos en el CalDAV
    $smw->delEvent($ocupacion['caldav_id']);
    //Lo borramos en la BBDD
    $ret = $app['db']->delete('ocupacion',array('id'=>$ocupacion['id']));
    if ($ret == 1 ) {
        return $app->json(array("estado"=> "ok", 
            "mensaje"=> "Eliminado la ocupacion del usuario ".$id_user." al servicio ".$id_servicio." el dia ".$fecha));
    } else {
        return $app->json(array("estado"=> "ko", 
            "mensaje"=> "No se ha podido eliminar la ocupacion del usuario ".$id_user." al servicio ".$id_servicio." el dia ".$fecha));
    }
})
->bind('ocupacion-servicio-del')
->assert('id_user', '\d+') //nos aseguramos que nos pasan un decimal
->assert('id_servicio', '\d+') //nos aseguramos que nos pasan un decimal
;


/***************************************************************************** /
 * Funcione que manejas ocupaciones que NO son de servicio (vacaciones, etc) * /
 * Trabajamos por meses                                                      * /
 ******************************************************************************/


/*
 * Devolvemos un HTML con la tabla de ocupacion usuario/servicio de un día. 
 * Esta tabla puede ser estatica (iconos) o dinámica (inputs tipo check). 
 * Con la dinamica llamamos vía AJAX a las funciones de añadir y borrar ocupación
 */
$ocupacion->get('/otros/{tipo}/{ano}/{mes}/', function ($tipo,$ano,$mes) use ($app) {
    $tipo = $app->escape($tipo);
    $lista_ocupaciones = array();
    //Usamos la librería calendr para sacar los días del mes y año
    $calendario_mes = $app['calendr']->getMonth($ano, $mes);
    //var_dump($calendario_mes);
    $lista_usuarios = $app['db']->fetchAll('SELECT * FROM usuarios');
    //recorremos los días del mes
    foreach($calendario_mes->getDays()  as $dia) {
        $lista_ocupaciones[$dia->format("Ymd")] = array();
        $lista_ocupaciones[$dia->format("Ymd")]['ocupaciones'] = array();
        $lista_ocupaciones[$dia->format("Ymd")]['festivo'] = 0 ;
        
        $lista_ocupaciones[$dia->format("Ymd")]['dia_mes'] = $dia->format("d");
        //FIXME comprobar con el calendario laboral??
        if ($dia->__toString() == "Sunday" or $dia->__toString() == "Saturday") {
            $lista_ocupaciones[$dia->format("Ymd")]['festivo'] = 1 ;
        }
        
        foreach($lista_usuarios as  $usuario) {
            //echo 'SELECT count(*) AS activo FROM ocupacion_otros WHERE user_id = '.$usuario['id'].' AND tipo = '.$tipo.' AND fecha = '.$dia->format("Ymd") ."<br>";
            $activo = $app['db']->fetchAssoc('SELECT count(*) AS activo FROM ocupacion WHERE user_id = ? AND tipo_ocupacion = 2 AND tipo_otro = ? AND fecha = ?',array($usuario['id'],$tipo,$dia->format("Ymd")));
            $lista_ocupaciones[$dia->format("Ymd")]['ocupaciones'][$usuario['username']]= array(
                "dia" => $dia->format("Ymd"),
                "user_id" => $usuario['id'],
                "activo" => $activo['activo']
                );
        }
    };
    //var_dump($lista_ocupaciones);
    return $app['twig']->render('ocupacion-otros-tabla.html',array('ano'=>$ano,'mes'=> $mes,'lista_usuarios'=>$lista_usuarios,'lista_ocupaciones'=>$lista_ocupaciones));
})
->bind('ocupacion-otros-ano-mes-html')
->assert('tipo', '\d+') //nos aseguramos que nos pasan un decimal
->assert('ano', '\d+') //nos aseguramos que nos pasan un decimal
->assert('mes', '\d+') //nos aseguramos que nos pasan un decimal
;


/*
 * Añadimos un día de ocupacion.
 */
$ocupacion->match('/otros/add/{tipo}/{id_usuario}/{fecha}/', function ($tipo,$id_usuario,$fecha) use ($app) {
    //Compramos si ya tiene ocupacion ese día
    $respuesta = $app['db']->fetchAssoc('SELECT count(*) AS ocupado FROM ocupacion WHERE user_id = ? AND fecha = ?',array($id_usuario,$fecha));
    if ($respuesta['ocupado'] > 0) {
        return $app->json(array("estado"=> "ko", 
        "mensaje"=> "Ya tiene programado el día con un vaciación, graciable, ... Por favor primero libere el día ".$fecha));
    }
    //Lo guardamos en el CalDAV
    $smw = new Etxea\SabreMW($app['db']);
    $user = $app['db']->fetchAssoc('SELECT * FROM usuarios WHERE id = ?',array($id_usuario));
    //FIXME esto a BBDD o conf!
    $tipos = array(1=>"Vacaciones",2=>"Graciables",3=>"Baja laboral",4=>"Congreso",5=>"Ivestigacion",6=>"Reunion");
    $calendar_id = $smw->getUserCalendar($user['username']);
    $evento = $smw->addEvent($calendar_id,$tipos[$tipo],$tipos[$tipo],$fecha);
    //Lo guardamos en BBDD
    $app['db']->insert('ocupacion',array('user_id'=>$id_usuario, 'tipo_ocupacion' => 2,'tipo_otro'=>$tipo,'fecha'=>$fecha,'caldav_id'=>$evento));
    return $app->json(array("estado"=> "ok", 
        "mensaje"=> "Agregado la ocupación de tipo ".$tipos[$tipo]." el ".$fecha." al usuario ".$id_usuario." con el ID en caldav ".$evento));
})
->bind('ocupacio-otros-add')
->assert('tipo', '\d+') //nos aseguramos que nos pasan un decimal
->assert('id_usuario', '\d+') //nos aseguramos que nos pasan un decimal
;

/*
 * Eliminamos un día de ocupacion.
 */
$ocupacion->match('/otros/del/{tipo}/{id_usuario}/{fecha}/', function ($tipo,$id_usuario,$fecha) use ($app) {
    //FIXME esto a BBDD o conf!
    $tipos = array(1=>"Vacaciones",2=>"Graciables",3=>"Baja laboral",4=>"Congreso",5=>"Ivestigacion",6=>"Reunion");
    $smw = new Etxea\SabreMW($app['db']);
    //Lo buscamos  en BBDD porque necesitamos el caldav_id
    $ocupacion = $app['db']->fetchAssoc('SELECT * FROM ocupacion WHERE user_id = ? AND tipo_ocupacion = 2 AND tipo_otro = ? AND fecha = ?',array($id_usuario,$tipo,$fecha));
    //Lo borramos en el CalDAV
    $smw->delEvent($ocupacion['caldav_id']);
    //Lo borramos en la BBDD
    $ret = $app['db']->delete('ocupacion',array('id'=>$ocupacion['id']));
    if ($ret == 1 ) {
        return $app->json(array("estado"=> "ok", 
            "mensaje"=> "Eliminado la ocupacion del usuario ".$id_usuario." del tipo ".$tipos[$tipo]." el dia ".$fecha));
    } else {
        return $app->json(array("estado"=> "ko", 
            "mensaje"=> "No se ha podido eliminar la ocupacion del usuario ".$id_usuario." del ".$tipos[$tipo]." el dia ".$fecha));
    }
})
->bind('ocupacion-otros-del')
->assert('tipo', '\d+') //nos aseguramos que nos pasan un decimal
->assert('id_usuario', '\d+') //nos aseguramos que nos pasan un d
;



return $ocupacion;

?>
