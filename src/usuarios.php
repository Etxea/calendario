<?php

//FIXME esto lod ebería hacer el autoload
include_once "../vendor/etxea/sabremw/lib/SabreMW/SabreMW.php";


$usuarios = $app['controllers_factory'];
$usuarios->get('/', function () use ($app) {
    $lista_usuarios = $app['db']->fetchAll('SELECT * FROM users WHERE borrado <> 1');
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
        ->add('roles', 'choice', array(
            'choices' => array(0 => 'estandar', 1 => 'administrador'),
            'expanded' => true,
        ))
        ->getForm();
    if ($app['request']->getMethod() == "POST" ) {
        
        $form->bind($app['request']);
        if ($form->isValid()) {
            $data = $form->getData();
           
            // La pass para Sabre
            $digesta1 = md5($data['username'].':SabreDAV:'.$data['password']);
            $data['digesta1'] = $digesta1;
            //Codificimaos la pass
            $password =  $app['security.encoder.digest']->encodePassword($data['password'], '');
            $data['password'] = $password;
            $app['db']->insert('users',$data);
            //Vamos con el alta en CalDAV
            $smw = new Etxea\SabreMW($app['db']);
            $smw->addUser($data['username']);
            $mensaje = "Usuario creado";
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
    $usuario = $app['db']->fetchAssoc('SELECT * FROM users WHERE id = ?',array($id));
    $form = $app['form.factory']->createBuilder('form', $usuario)
        ->add('nombre')
        ->add('apellidos')
        ->add('username')
        ->add('password')
        ->add('categoria', 'choice', array(
            'choices' => array(1 => 'Jefe de servicio/sección', 2 => 'Adjunto', 3 => 'Residente'),
            'expanded' => true,
        ))
        ->add('roles', 'choice', array(
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
            // La pass para Sabre
            $digesta1 = md5($data['username'].':SabreDAV:'.$data['password']);
            $data['digesta1'] = $digesta1;
            
            //Codificimaos la pass
            $password =  $app['security.encoder.digest']->encodePassword($data['password'], '');
            $data['password'] = $password;
            
            //Lo editamos
            var_dump($data);
            $ret = $app['db']->update('users',$data,array('id'=>$id));
            $mensaje = "Actualizado";
            
            
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
    $usuario = $app['db']->fetchAssoc('SELECT * FROM users WHERE id = ?',array($id));
    if ($app['request']->getMethod() == "POST" ) { //Si es post borramos
        $app['monolog']->addInfo("Vamos a borrar ".$usuario['username']);
        $smw = new Etxea\SabreMW($app['db']);
        //Borramos todos los eventos del sabre
        $smw->delUser($usuario['username']);
        //FIXME esto deberia ser poner como borrado
        //Borramos todo los eventos de ocupacion 
        $app['db']->delete('ocupacion',array('user_id'=>$usuario['id']));
        //Borraos el usuario
        $app['db']->update('users',array('borrado'=>1),array('id'=>$usuario['id']));
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
