<?php
require 'vendor/autoload.php';
// Use the factory to get your period
$factory = new CalendR\Calendar;
$month = $factory->getMonth(2014, 03);
//var_dump($month);
?>
<html>
<body>
<h1>Calendario</h1>
El mes empieza el <?php echo $month->getBegin()->format('Y-m-d H:i:s') ; ?> y termina el <?php echo $month->getEnd()->format('Y-m-d H:i:s'); ?>.
<table>
    <tr>
	<td>Lunes</td>
	<td>Martes</td>
	<td>Miercoles</td>
	<td>Jueves</td>
	<td>Viernes</td>
	<td>Sábado</td>
	<td>Domingo</td>
    </tr>
    <?php // Iterate over your month and get weeks ?>
    <?php foreach ($month as $week): ?>
    <tr>
        <?php // Iterate over your month and get days ?>
        <?php foreach ($week as $day): ?>
            <?php //Check days that are out of your month ?>
            <td><?php echo $day->getBegin()->format('m/d') ; ?></td>
        <?php endforeach ?>
    </tr>
    <?php endforeach ?>
</table>
</body>
</html>
