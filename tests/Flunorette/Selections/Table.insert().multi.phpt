<?php

/**
 * Test: Flunorette\Selections: Multi insert operations
 * @dataProvider? ../databases.ini
*/



require __DIR__ . '/../connect.inc.php'; // create $connection

Flunorette\Helpers::loadFromFile($connection, __DIR__ . "/../files/{$driverName}-nette_test1.sql");


Assert::same(3, $connection->table('author')->count());
$connection->table('author')->insert(array(
	array(
		'name' => 'Catelyn Stark',
		'web' => 'http://example.com',
		'born' => new DateTime('2011-11-11'),
	),
	array(
		'name' => 'Sansa Stark',
		'web' => 'http://example.com',
		'born' => new DateTime('2021-11-11'),
	),
)); // INSERT INTO `author` (`name`, `web`, `born`) VALUES ('Catelyn Stark', 'http://example.com', '2011-11-11 00:00:00'), ('Sansa Stark', 'http://example.com', '2021-11-11 00:00:00')
Assert::same(5, $connection->table('author')->count());

$connection->table('book_tag')->where('book_id', 1)->delete(); // DELETE FROM `book_tag` WHERE (`book_id` = ?)

Assert::same(4, $connection->table('book_tag')->count());
$connection->table('book')->get(1)->related('book_tag')->insert(array(// SELECT * FROM `book` WHERE (`id` = ?)
	array('tag_id' => 21),
	array('tag_id' => 22),
	array('tag_id' => 23),
)); // INSERT INTO `book_tag` (`tag_id`, `book_id`) VALUES (21, 1), (22, 1), (23, 1)
Assert::same(7, $connection->table('book_tag')->count());
