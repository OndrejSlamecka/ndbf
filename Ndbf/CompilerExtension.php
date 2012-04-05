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

class CompilerExtension extends \Nette\Config\CompilerExtension
{

	public function loadConfiguration()
	{
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		/* --- Repositories --- */
		if (isset($config['repositories'])) {
			foreach ($config['repositories'] as $name => $value) {

				if (is_array($value) && !isset($value['class'])) {
					throw new \InvalidArgumentException("Repository $name doesn't have defined 'class' parameter");
				}

				$className = is_array($value) ? $value['class'] : $value;
				$serviceDefinition = $builder->addDefinition($this->prefix('repositories.' . $name))
						->setClass($className);

				if (is_array($value) && isset($value['setup'])) {
					foreach ($value['setup'] as $setup) {
						$attributes = isset($setup->attributes) ? $setup->attributes : array();
						$attributes = $this->compiler->filterArguments($attributes);

						$value = is_string($setup) ? $setup : $setup->value;
						$serviceDefinition->addSetup($value, $attributes);
					}
				}
			}
		}

		// Ndbf\RepositoryManager service
		$builder->addDefinition($this->prefix('repositoryManager'))
				->setClass('Ndbf\RepositoryManager');
	}

}