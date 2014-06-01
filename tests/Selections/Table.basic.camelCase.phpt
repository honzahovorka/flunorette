<?php

/**
 * Test: Nette\Database\Table: Basic operations with camelCase name conventions.
 *
 * @author     David Grudl
 * @author     Jan Skrasek
 * @dataProvider? databases.ini
*/



require __DIR__ . '/connect.inc.php'; // create $connection

Flunorette\Helpers::loadFromFile($connection, __DIR__ . "/{$driverName}-nette_test2.sql");
$connection->setDatabaseReflection(new \Flunorette\Reflections\DiscoveredReflection($connection));


$titles = array();
foreach ($connection->table('nUsers')->order('nUserId') as $user) {
	foreach ($user->related('nUsers_nTopics')->order('nTopicId') as $userTopic) {
		$titles[$userTopic->nTopic->title] = $user->name;
	}
}

Assert::same(array(
	'Topic #1' => 'John',
	'Topic #3' => 'John',
	'Topic #2' => 'Doe',
), $titles);
