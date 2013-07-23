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

class CompilerExtension extends \Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		/* --- Repositories --- */
		if (isset($config['repositories'])) {
			foreach ($config['repositories'] as $name => $definition) {

				$serviceDefinition = $builder->addDefinition($this->prefix('repositories.' . $name));

				// Class
				if (is_string($definition)) {
					$serviceDefinition->setClass($definition);
				} else {
					if (!isset($definition['class'])) {
						throw new \InvalidArgumentException("Repository $name doesn't have defined 'class' parameter");
					}

					if (is_string($definition['class'])) {
						$serviceDefinition->setClass($definition['class']);
					} else {
						$serviceDefinition->setClass($definition['class']->value, $definition['class']->attributes);
					}
				}

				// Table name
				$serviceDefinition->addSetup('setTableName', $name);

				// Primary key
				if (isset($definition['primaryKey'])) {
					$serviceDefinition->addSetup('setTablePrimaryKey', $definition['primaryKey']);
				}

				// Setup
				$serviceDefinition->addSetup('injectConnection');
				$serviceDefinition->addSetup('injectSelectionFactory');
				if (is_array($definition) && isset($definition['setup'])) {
					foreach ($definition['setup'] as $setup) {
						$attributes = isset($setup->attributes) ? $setup->attributes : array();
						$attributes = $this->compiler->filterArguments($attributes);

						$val = is_string($setup) ? $setup : $setup->value;
						$serviceDefinition->addSetup($val, $attributes);
					}
				}
			}
		}

	}

}
