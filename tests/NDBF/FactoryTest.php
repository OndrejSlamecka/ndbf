<?php

class FactoryTest extends PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $instance = new \NDBF\Factory();
        
        // Test if returned value is instance of Nette\Database\Connection
        $this->assertInstanceOf('Nette\Database\Connection', $instance->createService(DB_DSN, DB_USER, DB_PASSWORD) );
    }

}