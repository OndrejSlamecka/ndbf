<?php
/**
 * This file is a part of the NDBF library
 *
 * @copyright (c) Ondrej Slamecka (http://www.slamecka.cz)
 *
 * License can be found within the file license.txt in the root folder.
 *
 */

namespace Ndbf;

/**
 * Basic repository class
 */
class Repository extends \Nette\Object
{
	/* ---------------------------- VARIABLES ------------------------------- */

	/** @var Nette\Database\Connection */
	protected $connection;

	/** @var string Associated table name */
	protected $tableName;

	/** @var string Table unique identifier - primary key */
	protected $tablePrimaryKey;

	/* ------------------------ CONSTRUCTOR, DESIGN ------------------------- */

	/**
	 * Always call parent constructor!
	 */
	public function __construct()
	{
		// Table name for own repositories
		$tableName = get_class($this);
		$tableName = substr($tableName, strrpos($tableName, '\\') + 1);
		$this->setTableName($tableName);
	}

	/**
	 * @internal
	 */
	public function setConnection(\Nette\Database\Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @internal
	 */
	public function setTableName($tableName)
	{
		$this->tableName = strtolower($tableName); // Lowercase convention!
	}

	/**
	 * @internal
	 */
	public function setTablePrimaryKey($tablePrimaryKey)
	{
		$this->tablePrimaryKey = $tablePrimaryKey;
	}

	/* ---------------------- Nette\Database EXTENSION ---------------------- */

	/**
	 * @return Nette\Database\Table\Selection
	 */
	final public function table()
	{
		return $this->connection->table($this->tableName);
	}

	/**
	 * @return Nette\Database\Table\Selection
	 */
	final public function select($columns = '*')
	{
		return $this->connection->table($this->tableName)->select($columns);
	}

	/**
	 * Returns all rows as an associative array.
	 * @param  string
	 * @param  string column name used for an array value or an empty string for the whole row
	 * @return array
	 */
	public function fetchPairs($key, $val = '')
	{
		return $this->table()->fetchPairs($key, $val);
	}

	/**
	 * Counts table's rows.
	 * @param string $column
	 * @return int
	 */
	public function count($column = '')
	{
		return $this->table()->count($column);
	}

	/**
	 * Deletes entity from db.
	 * @param array $conditions
	 * @throws LogicException, InvalidArgumentException
	 */
	public function delete($conditions)
	{
		$this->table()->where($conditions)->delete();
	}

	/**
	 * Saves record
	 * @param array $record
	 * @param string $tablePrimaryKey
	 */
	public function save(&$record, $tablePrimaryKey = NULL)
	{
		// Determine table primary key
		if ($this->tablePrimaryKey !== NULL) {
			$tablePrimaryKey = $this->tablePrimaryKey;
		} elseif ($tablePrimaryKey === NULL) {
			throw new \InvalidArgumentException("Missing second parameter for 'NDBF::save'");
		}

		// If there is no ID, we MUST insert
		if (!isset($record[$tablePrimaryKey])) {
			$insert = true;
		} else {
			// There is an ID
			// Following condition allows restoring deleted items
			if ($this->select($tablePrimaryKey)->where($tablePrimaryKey, $record[$tablePrimaryKey])->fetch()) // Is this entity already stored?
				$insert = false; // Yes it is, so we'll update it
			else
				$insert = true; // No it isn't so insert
		}


		if ($insert) {
			$this->table()->insert($record);

			// Set last inserted item id
			$record[$tablePrimaryKey] = $this->connection->lastInsertId();
		} else {
			$this->table()->where($tablePrimaryKey, $record[$tablePrimaryKey])->update($record);
		}
	}

}
