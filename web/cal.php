<?php

/*

CalendarServer example

This server features CalDAV support

*/

/* Database */
include '../config/sabre.php';
$pdo = new \PDO("mysql:dbname=$db_name",$db_user,$db_pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

//Mapping PHP errors to exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

// Files we need
require_once '../vendor/autoload.php';

// Backends
$authBackend = new \Sabre\DAV\Auth\Backend\PDO($pdo);
$calendarBackend = new \Sabre\CalDAV\Backend\PDO($pdo);
$principalBackend = new \Sabre\DAVACL\PrincipalBackend\PDO($pdo);

// Directory structure 
$tree = array(
    new \Sabre\CalDAV\Principal\Collection($principalBackend),
    new \Sabre\CalDAV\CalendarRootNode($principalBackend, $calendarBackend),
);

$server = new \Sabre\DAV\Server($tree);

if (isset($baseUri))
    $server->setBaseUri($baseUri);

/* Server Plugins */
$authPlugin = new \Sabre\DAV\Auth\Plugin($authBackend,'SabreDAV');
$server->addPlugin($authPlugin);

$aclPlugin = new \Sabre\DAVACL\Plugin();
$server->addPlugin($aclPlugin);

$caldavPlugin = new \Sabre\CalDAV\Plugin();
$server->addPlugin($caldavPlugin);

// Support for html frontend
$browser = new \Sabre\DAV\Browser\Plugin();
$server->addPlugin($browser);

// And off we go!
$server->exec();
