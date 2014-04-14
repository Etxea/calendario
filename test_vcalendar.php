<?php

require 'vendor/autoload.php';

$event = Sabre\VObject\Component::create('VEVENT');

$event->SUMMARY = 'Curiosity launch';
$event->DTSTART = '20111126T150202Z';
$event->LOCATION = 'Cape Carnival';
echo "<h1>Evento</h1>";
echo "<pre>".$event->serialize()."</pre>";


$vcalendar = new Sabre\VObject\Component\VCalendar();
$vcalendar->add($event);
echo "<h1>calendario</h1>";
echo "<pre>".$vcalendar->serialize()."</pre>";

/* Database */
$pdo = new \PDO('mysql:dbname=sabre','root','t3cn0farsa!');
#$pdo = new \PDO('sqlite:/var/www/calendar/data/db.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//Backends sabre
$authBackend = new \Sabre\DAV\Auth\Backend\PDO($pdo);
$calendarBackend = new \Sabre\CalDAV\Backend\PDO($pdo);
$principalBackend = new \Sabre\DAVACL\PrincipalBackend\PDO($pdo);


echo "<h1>Principals</h1>";
$principals = $principalBackend->getPrincipalsByPrefix('principals');

foreach($principals as $user) {
	
	echo "<h2>calendarios de ".$user['uri']." </h2>";
	//var_dump($user);
	$calendarios = $calendarBackend->getCalendarsForUser($user['uri']);
	foreach($calendarios as $calendario) {
		//echo "<pre>"; var_dump($calendario); echo "</pre>";
		echo "<h3>Eventos</h3>";
		foreach ($calendarBackend->getCalendarObjects($calendario['id']) as $evento_obj) {
			$evento_data = $calendarBackend->getCalendarObject($calendario['id'],$evento_obj['uri'])["calendardata"];
			$evento = Sabre\VObject\Reader::read($evento_data);
			echo "<pre>"; var_dump($evento); echo "</pre>";
			echo "Empieza ".$calendar->VEVENT->DTSTART." acaba ".$calendar->VEVENT->DTEND;
			
		}
		
	}
		
}

echo "<h1>Fin</h1>";

?>
