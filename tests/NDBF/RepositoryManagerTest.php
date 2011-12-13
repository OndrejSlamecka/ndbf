<?php
/**
 * This file is a part of the NDBF library
 *
 * @copyright (c) 2011 Ondrej Slamecka (http://www.slamecka.cz)
 * 
 * License can be found within the file license.txt in the root folder.
 * 
 */

class RepositoryManagerTest extends PHPUnit_Framework_TestCase
{

    public function testGetRepository()
    {
        $rm = new \NDBF\RepositoryManager(\Nette\Environment::getContext());
        self::assertInternalType('object', $rm->getRepository('FooBarTestRepository'));
    }

    /**
     * @depends testGetRepository
     */
    public function test__get()
    {
        $rm = new \NDBF\RepositoryManager(\Nette\Environment::getContext());
        self::assertInternalType('object', $rm->FooBarTestRepository);
    }

}
