<?php

$usuarios = $app['controllers_factory'];
$usuarios->get('/', function () use ($app) {
    $lista_usuarios = $app['db']->fetchAll('SELECT * FROM usuarios');
    return $app['twig']->render('usuarios.html', array('lista_usuarios'=>$lista_usuarios));
})
->bind('usuarios')
;

$usuarios->match('/alta', function () use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('nombre')
        ->add('apellidos')
        ->add('username')
        ->add('password')
        ->add('categoria', 'choice', array(
            'choices' => array(1 => 'Jefe de servicio/sección', 2 => 'Adjunto', 3 => 'Residente'),
            'expanded' => true,
        ))
        ->add('rol', 'choice', array(
            'choices' => array(0 => 'estandar', 1 => 'administrador'),
            'expanded' => true,
        ))
        ->getForm();
    if ($app['request']->getMethod() == "POST" ) {
        
        $form->bind($app['request']);
        if ($form->isValid()) {
            $data = $form->getData();
            //ahora a actualizar la BBDD
            unset($data['id']);
            $app['db']->insert('usuarios',$data);
            $mensaje = "usuario creado";
            return $app->redirect($app['url_generator']->generate('usuarios'));
        } else {
            $mensaje = "Formulario mal";
        }
    } else {
        $mensaje = "Introduce los datos";
    }
    return $app['twig']->render('usuarios-alta.html', array('mensaje' => $mensaje, 'form' => $form->createView()));
})
->bind('usuarios-alta')
;

$usuarios->match('/editar/{id}', function ($id) use ($app) {
    $usuario = $app['db']->fetchAssoc('SELECT * FROM usuarios WHERE id = ?',array($id));
    $form = $app['form.factory']->createBuilder('form', $usuario)
        ->add('nombre')
        ->add('apellidos')
        ->add('username')
        ->add('password')
        ->add('categoria', 'choice', array(
            'choices' => array(1 => 'Jefe de servicio/sección', 2 => 'Adjunto', 3 => 'Residente'),
            'expanded' => true,
        ))
        ->add('rol', 'choice', array(
            'choices' => array(0 => 'estandar', 1 => 'administrador'),
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
            $app['db']->update('usuarios',$data,array('id' => $id));
            // hay que cambiar la pass en caldav
        } else {
            $mensaje = "Formulario mal";
        }
    } else {
        $mensaje = "vamos a editar";
        
    }
    
    return $app['twig']->render('usuarios-editar.html', array('id_usuario'=> $id ,'mensaje'=>$mensaje,'usuario'=>$usuario,'form' => $form->createView()));
})
->bind('usuarios-editar')
->assert('id', '\d+') //nos aseguramos que nos pasan un decimal
;

return $usuarios

?>
