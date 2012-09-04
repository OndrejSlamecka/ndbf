<?php
/**
 * NDBF tests
 * Ondrej Slamecka, www.slamecka.cz
 *
 * To run tests with following settings, you need to have Nette in the same folder as NDBF
 * Edit test.config.neon
 *
 */
// Define paths
define('TESTS_DIR', __DIR__);

// Require libraries
require_once 'PHPUnit/Autoload.php';
require_once __DIR__ . '/../vendor/nette/nette/Nette/loader.php';

// Setup configurator
$configurator = new Nette\Config\Configurator();
$configurator->setTempDirectory(TESTS_DIR . '/temp');

// Setup RobotLoader
$configurator->createRobotLoader()
		->addDirectory(__DIR__ . '/../Ndbf')
		->register();

require_once __DIR__ . '/Testtable.php';

$configurator->onCompile[] = function ($cf, $compiler) { $compiler->addExtension('ndbf', new Ndbf\CompilerExtension); };

$configurator->addConfig('test.config.neon', FALSE);
$container = $configurator->createContainer();
