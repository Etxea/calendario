<?php

$servicios = $app['controllers_factory'];
$servicios->get('/', function () use ($app) {
    $lista_servicios = $app['db']->fetchAll('SELECT servicios.id AS id ,servicios_tipo.nombre AS tipo, servicios.nombre AS nombre, estado, nombre_corto FROM servicios, servicios_tipo WHERE servicios.tipo = servicios_tipo.id ORDER BY servicios.id ASC');
    
    $lista_tipos_servicios = $app['db']->fetchAll('SELECT * FROM servicios_tipo ORDER BY id ASC');
    return $app['twig']->render('servicios.html', array('lista_servicios'=>$lista_servicios,'lista_tipos_servicios'=>$lista_tipos_servicios));
})
->bind('servicios')
;


$servicios->match('/alta', function () use ($app) {
    //Preparamos el listado para poder rellenar el choice_list del form
    
    $tipo_servcios = array();
    $statement = $app['db']->executeQuery('SELECT id,nombre FROM servicios_tipo ORDER BY id ASC');
    while($tipo = $statement->fetch()) {
        $tipo_servcios[$tipo['id']]=$tipo['nombre'];
    }
    
    $form = $app['form.factory']->createBuilder('form')
        ->add('nombre')
        ->add('nombre_corto')
        ->add('tipo', 'choice', array(
            'choices' => $tipo_servcios,

        ))
        ->add('estado', 'choice', array(
            'choices' => array(1 => "activo", 0 => "Inactivo"),

        ))
        ->getForm();
    
    if ($app['request']->getMethod() == "POST" ) {
        $form->bind($app['request']);
        if ($form->isValid()) {
            $data = $form->getData();
            //ahora a actualizar la BBDD
            unset($data['id']);
            $app['db']->insert('servicios',$data);
            $mensaje = "usuario creado";
            return $app->redirect($app['url_generator']->generate('servicios'));
        } else {
            $mensaje = "Formulario mal";
        }
    } else {
        $mensaje = "Introduce los datos";
    }
    return $app['twig']->render('servicio-alta.html', array('mensaje' => $mensaje, 'form' => $form->createView()));
})
->bind('servicios-alta')
;

$servicios->match('/editar/{id}', function ($id) use ($app) {
    //Preparamos el listado para poder rellenar el choice_list del form
    $tipo_servcios = array();
    $statement = $app['db']->executeQuery('SELECT id,nombre FROM servicios_tipo ORDER BY id ASC');
    while($tipo = $statement->fetch()) {
        $tipo_servcios[$tipo['id']]=$tipo['nombre'];
    }
    $servicio = $app['db']->fetchAssoc('SELECT * FROM servicios WHERE id = ?',array($id));
    
    $form = $app['form.factory']->createBuilder('form', $servicio)
        ->add('nombre')        ->add('nombre_corto')
        ->add('tipo', 'choice', array(
            'choices' => $tipo_servcios,
            'expanded' => true,
        ))
        ->add('estado', 'choice', array(
            'choices' => array(1 => "activo", 0 => "Inactivo"),
            'expanded' => true,
        ))
        ->getForm();
    if ($app['request']->getMethod() == "POST" ) {
        $form->bind($app['request']);
        if ($form->isValid()) {
            $mensaje = "Vamos guardar";
            $data = $form->getData();
            //ahora a actualizar la BBDD
            unset($data['id']);
            $app['db']->update('servicios',$data,array('id' => $id));
            // hay que cambiar la pass en caldav
        } else {
            $mensaje = "Formulario mal";
        }
    } else {
        $mensaje = "vamos a editar";
        
    }
    
    return $app['twig']->render('servicio-editar.html', array('id_usuario'=> $id ,'mensaje'=>$mensaje,'servicio'=>$servicio,'form' => $form->createView()));
})
->bind('servicios-editar')
->assert('id', '\d+') //nos aseguramos que nos pasan un decimal
;


/*
 * Gestion d elos tipos de servicios
 */
$servicios->match('/tipo/alta', function () use ($app) {
    
    $form = $app['form.factory']->createBuilder('form')
        ->add('nombre')
        ->add('id')
        ->getForm();
    if ($app['request']->getMethod() == "POST" ) {
        
        $form->bind($app['request']);
        if ($form->isValid()) {
            $data = $form->getData();
            $app['db']->insert('servicios_tipo',$data);
            $mensaje = "Tipo de servicio dado de alta";
            return $app->redirect($app['url_generator']->generate('servicios'));
        } else {
            $mensaje = "Formulario mal";
        }
    } else {
        $mensaje = "Introduce los datos";
    }
    return $app['twig']->render('servicio-tipo-alta.html', array('mensaje' => $mensaje, 'form' => $form->createView()));
})
->bind('servicios-tipo-alta')
;

$servicios->match('/tipo/editar/{id}', function ($id) use ($app) {
    $servicio_tipo = $app['db']->fetchAssoc('SELECT * FROM servicios_tipo WHERE id = ?',array($id));
    var_dump($servicio_tipo);
    $form = $app['form.factory']->createBuilder('form', $servicio_tipo)
        ->add('id')
        ->add('nombre')
        ->getForm();
    if ($app['request']->getMethod() == "POST" ) {
        $form->bind($app['request']);
        if ($form->isValid()) {
            $mensaje = "Vamos guardar";
            $data = $form->getData();
            //ahora a actualizar la BBDD
            $app['db']->update('servicios_tipo',$data,array('id' => $id));
            return $app->redirect($app['url_generator']->generate('servicios'));
        } else {
            $mensaje = "Formulario mal";
        }
    } else {
        $mensaje = "vamos a editar";
        
    }
    
    return $app['twig']->render('servicio-tipo-editar.html', array('id_usuario'=> $id ,'mensaje'=>$mensaje,'servicio_tipo'=>$servicio_tipo,'form' => $form->createView()));
})
->bind('servicios-tipo-editar')
->assert('id', '\d+') //nos aseguramos que nos pasan un decimal
;



return $servicios

?>
