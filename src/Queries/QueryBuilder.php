<?php

namespace Flunorette\Queries;

use Flunorette\Connection;

/**
 * select methods
 * @method QueryBuilder select($column) add one or more columns in SELECT to query
 * @method QueryBuilder having($statement) add HAVING query
 * @method QueryBuilder offset(int $offset) add OFFSET to query
 *
 * delete methods
 * @method QueryBuilder from(string $table) add LIMIT to query
 *
 * update methods
 * //method \Flunorette\Queries\QueryBuilder	set($fieldOrArray, $value = null)
 *
 * insert methods
 * @method QueryBuilder ignore()
 * //method \Flunorette\Queries\QueryBuilder onDuplicateKeyUpdate($values)
 * //method \Flunorette\Queries\QueryBuilder values($values)
 *
 * common methods except insert
 * @method QueryBuilder join($statement) add LEFT JOIN to query ($statement can be 'table' name only or 'table:' means back reference)
 * @method QueryBuilder leftJoin($statement) add LEFT JOIN to query ($statement can be 'table' name only or 'table:' means back reference)
 * @method QueryBuilder innerJoin($statement) add INNER JOIN to query ($statement can be 'table' name only or 'table:' means back reference)
 * @method QueryBuilder where($statement) add WHERE to query
 * @method QueryBuilder wherePrimary($id) add WHERE with primary key
 * @method QueryBuilder groupBy($statement) add GROUP BY to query
 * @method QueryBuilder orderBy($statement) add ORDER BY to query
 * @method QueryBuilder limit(int $limit) add LIMIT to query
 *
 * @method QueryBuilder enableSmartJoin()
 * @method QueryBuilder disableSmartJoin()
 *
 */
class QueryBuilder {

	static protected $commonMethods = array('join', 'leftJoin', 'innerJoin', 'where', 'wherePrimary', 'orderBy', 'order', 'limit', 'enableSmartJoin', 'disableSmartJoin');

	static protected $selectMethods = array('select', 'having', 'offset', 'groupBy', 'group');

	static protected $deleteMethods = array('from');

	static protected $updateMethods = array(/* 'set' */);

	static protected $insertMethods = array('ignore', 'onDuplicateKeyUpdate', 'values');

	/** @var Connection */
	protected $connection;

	/** @var string */
	protected $table;

	/** @var array */
	protected $calls = array();

	function __construct($table, Connection $connection) {
		$this->table = $table;
		$this->connection = $connection;
	}

	public function __call($name, $arguments) {
		$this->calls[] = array($name, $arguments);
		return $this;
	}

	/** @return SelectQuery */
	public function buildSelectQuery() {
		$methods = array_merge(static::$selectMethods, static::$commonMethods);
		$query = new SelectQuery($this->table, $this->connection);
		$this->callMethods($methods, $query);
		return $query;
	}

	/** @return DeleteQuery */
	public function buildDeleteQuery() {
		$methods = array_merge(static::$deleteMethods, static::$commonMethods);
		$query = new DeleteQuery($this->table, $this->connection);
		$this->callMethods($methods, $query);
		return $query;
	}

	/** @return UpdateQuery */
	public function buildUpdateQuery($data) {
		$methods = array_merge(static::$updateMethods, static::$commonMethods);
		$query = new UpdateQuery($this->table, $this->connection);
		$this->callMethods($methods, $query);
		return $query->set($data);
	}

	/** @return InsertQuery */
	public function buildInsertQuery($values, $onDupliceKeyUpdate = null) {
		$methods = array_merge(static::$insertMethods);
		$query = new InsertQuery($this->table, $this->connection);
		$this->callMethods($methods, $query);
		if (null !== $onDupliceKeyUpdate) {
			$query->onDuplicateKeyUpdate($onDupliceKeyUpdate);
		}
		return $query->values($values);
	}

	protected function callMethods($methods, $query) {
		foreach ($this->calls as $call) {
			list($name, $args) = $call;
			if (in_array($name, $methods)) {
				call_user_func_array(array($query, $name), $args);
			}
		}
	}

}
