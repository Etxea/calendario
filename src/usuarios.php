<?php

//FIXME esto lod ebería hacer el autoload
include_once "../vendor/etxea/sabremw/lib/SabreMW/SabreMW.php";


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
            //Vamos con el alta en CalDAV
            $smw = new Etxea\SabreMW($app['db']);
            $smw->addUser($data['username'],$data['password']);
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
            // hay que cambiar la pass en caldav.
            //Primer generamos el password en md5
            $pass_md5 = md5($data['username'].':SabreDAV:'.$data['password']);
            $app['db']->update('users',array('username'=>$data['username'],'digesta1'=>$pass_md5),array('username',$data['username']));
            
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


$usuarios->match('/del/{id}', function ($id) use ($app) {
    $usuario = $app['db']->fetchAssoc('SELECT * FROM usuarios WHERE id = ?',array($id));
    if ($app['request']->getMethod() == "POST" ) { //Si es post borramos
        $app['monolog']->addInfo("Vamos a borrar ".$usuario['username']);
        $smw = new Etxea\SabreMW($app['db']);
        //Borramos todos los eventos del sabre
        $smw->delUser($usuario['username']);
        //Borramos todo los eventos de ocupacion de servicios
        $app['db']->delete('ocupacion_servicios',array('user_id'=>$usuario['id']));
        //Borramos todo los eventos de ocupacion otras
        $app['db']->delete('ocupacion_otros',array('user_id'=>$usuario['id']));
        //BOrraos el usuario
        $app['db']->delete('usuarios',array('id'=>$usuario['id']));
        return $app->redirect($app['url_generator']->generate('usuarios'));
    } else {
        return $app['twig']->render('usuarios-borrar.html',array("usuario"=>$usuario,'mensaje'=>"Confirmación"));
    }
})
->bind('usuarios-borrar')
->assert('id', '\d+') //nos aseguramos que nos pasan un decimal
;


return $usuarios

?>
