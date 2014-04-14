<?php

require 'vendor/autoload.php';


$app = new Silex\Application();

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});


$app->get('/calendario/{ano}/{semana}', function ($ano, $semana) use ($app) {
	// Use the factory to get your period
	$calendar_factory = new CalendR\Calendar;
	$week  = $calendar_factory->getWeek($ano, $semana);
	return "Queremos ver el $ano y semana $semana";


});


$app->run();

?>
