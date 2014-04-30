<?php

$servicios = $app['controllers_factory'];
$servicios->get('/', function () use ($app) {
    //$lista_servicios = $app['db']->fetchAll('SELECT * FROM servicios');
    $lista_servicios = array();
    return $app['twig']->render('servicios.html', array('lista_servicios'=>$lista_servicios));
})
->bind('servicios')
;

$servicios->match('/alta', function () use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('nombre')
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
    $usuario = $app['db']->fetchAssoc('SELECT * FROM servicios WHERE id = ?',array($id));
    $form = $app['form.factory']->createBuilder('form', $usuario)
        ->add('nombre')
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
    
    return $app['twig']->render('servicio-editar.html', array('id_usuario'=> $id ,'mensaje'=>$mensaje,'usuario'=>$usuario,'form' => $form->createView()));
})
->bind('servicios-editar')
->assert('id', '\d+') //nos aseguramos que nos pasan un decimal
;

return $servicios

?>
