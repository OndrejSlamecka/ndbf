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
$configurator->setTempDirectory(TESTS_DIR . '/temp');

// Setup RobotLoader
$configurator->createRobotLoader()
		->addDirectory(LIBS_DIR)
		->register();

require_once __DIR__ . '/ExampleRepository.php';

$configurator->onCompile[] = function ($cf, $compiler) { $compiler->addExtension('ndbf', new NDBF\CompilerExtension); };

$configurator->addConfig('test.config.neon', FALSE);
$container = $configurator->createContainer();