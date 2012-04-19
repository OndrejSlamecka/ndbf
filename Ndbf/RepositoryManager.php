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

class RepositoryManager
{

	/** @var Nette\DI\Container */
	private $container;

	/** @var Nette\Database\Connection */
	private $connection;

	/** @var array */
	private $instantiatedRepositories;

	/* ------------------------ CONSTRUCTOR, DESIGN ------------------------- */

	/**
	 * @param Nette\DI\Container $container
	 * @param Nette\Database\Connection $connection
	 */
	public function __construct(\Nette\DI\Container $container, \Nette\Database\Connection $connection)
	{
		$this->container = $container;
		$this->connection = $connection;
	}

	/**
	 * Returns service ndbf.repositories.<$repository>  if exists else instance of Ndbf\Repository
	 * @param string Repository name
	 * @return Ndbf\Repository
	 */
	public function getRepository($name)
	{
		if ($this->container->hasService('ndbf.repositories.' . $name)) {
			return $this->container->getService('ndbf.repositories.' . $name);
		} else {
			if (empty($this->instantiatedRepositories) || !in_array($name, array_keys($this->instantiatedRepositories))) {
				$instance = new Repository();
				$instance->setConnection($this->connection);
				$instance->setTableName($name);
				$this->instantiatedRepositories[$name] = $instance;
			}
			return $this->instantiatedRepositories[$name];
		}
	}

	/**
	 * Getter and shortuct for getRepository()
	 * @param string Repository name
	 * @return Ndbf\Repository
	 */
	public function __get($name)
	{
		return $this->getRepository($name);
	}

}
