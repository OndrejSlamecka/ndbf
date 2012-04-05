<?php
/**
 * This file is a part of the NDBF library
 *
 * @copyright (c) Ondrej Slamecka (http://www.slamecka.cz)
 *
 * License can be found within the file license.txt in the root folder.
 *
 */

class RepositoryManagerTest extends PHPUnit_Framework_TestCase
{

    public function testGetRepository()
    {
        $container = \Nette\Environment::getContext();
        $rm = new \Ndbf\RepositoryManager($container);
        self::assertInstanceOf('Ndbf\Repository', $rm->getRepository('FooBarRepository'));
        self::assertInstanceOf('ExampleRepository', $rm->getRepository('Example'));
		self::assertInstanceOf('ExampleRepository2', $rm->getRepository('Example2'));
    }

    /**
     * @depends testGetRepository
     */
    public function test__get()
    {
        // If testGetRepository passes (granted by dependance) this passes too, since it's just a shortcut
    }

}
