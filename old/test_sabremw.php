<?php

require 'vendor/autoload.php';

$sabre_mw = new SabreMW\SabreMW();

echo $sabre_mw->helloWorld();
echo $sabre_mw->testEvento();

?>
