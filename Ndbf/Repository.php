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

use Nette\Database\Context;

/**
 * Basic repository class
 */
class Repository extends \Nette\Object
{
	/* ---------------------------- VARIABLES ------------------------------- */

	/** @var Nette\Database\Context */
	protected $context;

	/** @var string Associated table name */
	protected $tableName;

	/** @var string Table unique identifier - primary key */
	protected $tablePrimaryKey = NULL;



	/* ------------------------ CONSTRUCTOR, DESIGN ------------------------- */


	/**
	 * @internal
	 */
	public function injectContext(\Nette\Database\Context $context)
	{
		$this->context = $context;
	}


	/**
	 * @internal
	 */
	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
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
		return $this->context->table($this->tableName);
	}


	/**
	 * @return Nette\Database\Table\Selection
	 */
	final public function select($columns = '*')
	{
		return $this->table()->select($columns);
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
	public function save(&$record)
	{
		// Decide whether to insert or update
		if ($this->tablePrimaryKey === NULL || !isset($record[$this->tablePrimaryKey])) {
			$insert = true;
		} else {
			// This condition allows restoring of deleted items
			if ($this->select($this->tablePrimaryKey)->where($this->tablePrimaryKey, $record[$this->tablePrimaryKey])->fetch()) {
				$insert = false;
			} else {
				$insert = true;
			}
		}

		// Perform
		if ($insert) {
			$this->table()->insert($record);
			if ($this->tablePrimaryKey !== NULL) {
				$record[$this->tablePrimaryKey] = $this->connection->getInsertId('"' . $this->tableName . '_' . $this->tablePrimaryKey . '_seq"');
			}
		} else {
			$this->table()->where($this->tablePrimaryKey, $record[$this->tablePrimaryKey])->update($record);
		}
	}


	/**
	 * Performs queries and commands given in callback during one transaction
	 * @param  callback $callback
	 */
	public function transaction($callback)
	{
		$this->context->beginTransaction();
		$return = call_user_func($callback);
		$this->context->commit();
		return $return;
	}

}
