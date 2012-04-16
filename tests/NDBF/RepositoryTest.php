<?php
/**
 * This file is a part of the NDBF library
 *
 * @copyright (c) Ondrej Slamecka (http://www.slamecka.cz)
 *
 * License can be found within the file license.txt in the root folder.
 *
 */

// TODO: Add dependencies
class RepositoryTest extends PHPUnit_Framework_TestCase
{

    private $instance;
    private $reflection;

    /** @var Nette\Database\Connection */
    private $connection;

    private function getClassProperty($name)
    {
        $property = $this->reflection->getProperty($name);
        $property->setAccessible(true);
        return $property->getValue($this->instance);
    }

    public function setUp()
    {
        parent::setUp();
        $container = \Nette\Environment::getContext();

        /** @var Nette\DI\Container */
        $this->connection = $container->getByType('Nette\Database\Connection');

        // Instance and reflection
        $this->instance = new \Ndbf\Repository();
        $this->instance->setConnection($this->connection);
        $this->instance->setTableName('Testtable');
        $this->reflection = new \Nette\Reflection\ClassType($this->instance);

        // Truncate
        $tableName = $this->getClassProperty('tableName');
        $this->connection->exec("TRUNCATE TABLE $tableName");
    }

    public function test__constructor()
    {
        self::assertInternalType('string', $this->getClassProperty('tableName'));
        self::assertFalse((bool) preg_match('~[A-Z]~', $this->getClassProperty('tableName')));
    }

    public function testTable()
    {
        self::assertInstanceOf('Nette\Database\Table\Selection', $this->instance->table($this->getClassProperty('tableName')));
    }

    public function testSave()
    {
        /* Inserting, returning received id in record, updating */
        $row = array();

        // Double save same record (same id - after first save written into record). One insert, one update
        $this->instance->save($row, 'id');
        $this->instance->save($row, 'id');

        // Expected: 1 item in db, id given to row
        $this->assertEquals(1, $this->connection->table($this->getClassProperty('tableName'))->count());
        //$this->assertEquals(1, $row['id'] ); // This assertion would be obsolete (if no id was given, two records would be present)


        /* Re-inserting deleted items */
        $row = array('id' => 2);

        $this->connection->exec('INSERT INTO ' . $this->getClassProperty('tableName'), $row);

        // In row id is 2, remove that record
        $this->connection->exec('DELETE FROM ' . $this->getClassProperty('tableName') . ' WHERE id=?', $row['id']);

        $this->instance->save($row, 'id'); // Re-insert of deleted item

        $this->assertEquals(2, $this->connection->table($this->getClassProperty('tableName'))->count());
    }

}
