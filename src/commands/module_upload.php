<?php
use Client\Client as Client;
use Client\Project as Project;
use Client\Console as Console;
use Client\Utils as Utils;

return array(
	'arg0'    => 'module:upload',
	'command' => 'module:upload',
	'description' => 'Upload a module to application',
	'run' => function($args) {
		$module_types = array('observers', 'routes', 'templates', 'channels');

		$client = new Client();
		foreach(Utils::glob(Project::root(Project::DIRECTORY_NAME) . '/**') as $module) {

			if (is_file($module)) {
				$module_type = basename(dirname($module));

				if (!in_array($module_type, $module_types)) {
					// echo "Invalid module type: '{$module_type}'." . PHP_EOL;
					continue;
				}

				Utils::check_php_syntax($module);
				echo "Uploading: '{$module}'" . PHP_EOL;

				$uploaded = $client->post('apps/modules', array(
					'module' => array(
						'name' => basename($module),
						'type' => $module_type,
						'code' => file_get_contents($module)
					)
				));

			}
		}

	}
);