<?php
/**
 * This file is a part of the NDBF library
 *
 * @copyright (c) 2011 Ondrej Slamecka (http://www.slamecka.cz)
 * 
 * License can be found within the file license.txt in the root folder.
 * 
 */

class FactoryTest extends PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $instance = new \NDBF\Factory();
        
        // Test if returned value is an instance of Nette\Database\Connection
        $this->assertInstanceOf('Nette\Database\Connection', $instance->createService(DB_DSN, DB_USER, DB_PASSWORD) );
    }

}
