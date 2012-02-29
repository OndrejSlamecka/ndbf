<?php
/**
 * This file is a part of the NDBF library
 *
 * @copyright (c) Ondrej Slamecka (http://www.slamecka.cz)
 *
 * License can be found within the file license.txt in the root folder.
 *
 */

namespace NDBF;

/**
 * Class responsible for managing repositories
 */
class RepositoryManager
{

    /** @var Nette\Database\Connection */
    private $connection;

    /** @var array */
    private $instantiated_repositories;

    /* ------------------------ CONSTRUCTOR, DESIGN ------------------------- */

    public function __construct(\Nette\Database\Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returns instance of Application\Repository\<$repository> if exists, instance of NDBF\Repository otherwise
     * @param string Repository name
     * @return NDBF\Repository
     */
    public function getRepository($name)
    {
        if (empty($this->instantiated_repositories) || !in_array($name, array_keys($this->instantiated_repositories))) {
            $class = 'Application\\Repository\\' . $name;

            if (class_exists($class)) {
                $instance = new $class($this, $this->connection, $name);
            } else {
                $instance = new Repository($this, $this->connection, $name);
            }
            $this->instantiated_repositories[$name] = $instance;
            $this->onRepositoryCreated($instance);
        }
        return $this->instantiated_repositories[$name];
    }

    /**
     * Shortuct for getRepository()
     * @param string Repository name
     * @return NDBF\Repository
     */
    public function __get($name)
    {
        return $this->getRepository($name);
    }

    /**
     * Callback called after repository was created
     * @param Repository $instance
     */
    protected function onRepositoryCreated(Repository $instance)
    {

    }

}