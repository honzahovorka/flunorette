<?php

/**
 * Test: Flunorette\Connection fetch methods.
 * @dataProvider? ../databases.ini
*/



require __DIR__ . '/../connect.inc.php'; // create $connection

Flunorette\Helpers::loadFromFile($connection, __DIR__ . "/../files/{$driverName}-nette_test1.sql");


// fetch
$row = $connection->fetch('SELECT name, id FROM author WHERE id = ?', 11);
//Assert::type( 'Flunorette\Row', $row );
Assert::same(array(
	'name' => 'Jakub Vrana',
	'id' => 11,
), (array) $row);


// fetchColumn
Assert::same(array('Jakub Vrana', 'David Grudl', 'Geek'), $connection->fetchColumn('SELECT name FROM author ORDER BY id'));


// fetchPairs
$pairs = $connection->fetchPairs('SELECT id, name FROM author WHERE id > ? ORDER BY id', 11);
Assert::same(array(
	12 => 'David Grudl',
	13 => 'Geek',
), $pairs);


// fetchAll
$arr = $connection->fetchAll('SELECT name, id FROM author WHERE id < ? ORDER BY id', 13);
foreach ($arr as &$row) {
	//Assert::type( 'Flunorette\Row', $row );
	$row = (array) $row;
}
Assert::equal(array(
	array('name' => 'Jakub Vrana', 'id' => 11),
	array('name' => 'David Grudl', 'id' => 12),
), $arr);