<?php

require 'vendor/autoload.php';

// Use the factory to get your period
$calendar_factory = new CalendR\Calendar;

/* Database */
$pdo = new \PDO('mysql:dbname=sabre','root','secreto');
#$pdo = new \PDO('sqlite:/var/www/calendar/data/db.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//Backends sabre
$authBackend = new \Sabre\DAV\Auth\Backend\PDO($pdo);
$calendarBackend = new \Sabre\CalDAV\Backend\PDO($pdo);
$principalBackend = new \Sabre\DAVACL\PrincipalBackend\PDO($pdo);

$app = new Silex\Application();
$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/views',));
//DEbugeamos
$app['debug'] = true;

$app->get('/', function () use ($app) {
	return $app['twig']->render('index.html');
});

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/calendario/{ano}/{num_semana}', function ($ano, $num_semana) use ($app, $calendar_factory,$principalBackend) {
	$week  = $calendar_factory->getWeek($app->escape($ano), $app->escape($num_semana));
	$principal = $principalBackend->getPrincipalsByPrefix("/");
	return $app['twig']->render('calendario.html',array('ano' => $ano, 'num_semana' =>$num_semana, 'semana' => $week));


})
->assert('ano', '\d+')
->assert('semana', '\d+');;


$app->run();

?>
