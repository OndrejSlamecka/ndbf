<?php
/**
 * NDBF tests
 * Ondrej Slamecka, www.slamecka.cz
 * 
 * To run tests with following settings, you need to have Nette in the same folder as NDBF
 * Edit variables $dsn, $user, $password according to your needs  
 *
 */
// Define paths
define('TESTS_DIR', __DIR__);
define('LIBS_DIR', TESTS_DIR . '/..');

// Require libraries
require_once 'PHPUnit/Autoload.php';
require_once LIBS_DIR . '/Nette/loader.php';

// Setup configurator
$configurator = new Nette\Config\Configurator();
$configurator->setCacheDirectory(TESTS_DIR . '/temp');

// Setup RobotLoader
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(LIBS_DIR);
$robotLoader->register();

// Configurate and setup database
$dsn = 'mysql:host=localhost;dbname=cms';
$user = 'root';
$password = 'root';

$configurator->container->addService('database', new \Nette\Database\Connection($dsn, $user, $password));