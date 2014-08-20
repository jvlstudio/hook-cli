<?php
use Client\Project as Project;

return array(
	'arg0'    => 'generate:channel',
	'command' => 'generate:channel <channel-name>',
	'description' => 'Generate custom channel class.',
	'run' => function($args) use ($commands) {

		if (strlen($args[1]) == 0) {
			Client\Console::error("You must specify a channel name.");
			die();
		}

		$dest = Project::root(Project::DIRECTORY_NAME) . '/channels/';
		$dest_file = $dest . $args[1] . '.php';
		@mkdir($dest, 0777, true);

		$template = file_get_contents(__DIR__ . '/../../templates/channel.php');
		$template = preg_replace('/{name}/', ucfirst($args[1]), $template);
		$template = preg_replace('/{channel}/', $args[1], $template);
		file_put_contents($dest_file, $template);

		echo "Custom channel created at '{$dest_file}'." . PHP_EOL;

		if ($editor = getenv('EDITOR')) {
			$descriptors = array(
				array('file', '/dev/tty', 'r'),
				array('file', '/dev/tty', 'w'),
				array('file', '/dev/tty', 'w')
			);
			$process = proc_open("{$editor} {$dest_file}", $descriptors, $pipes);
		}
	}
);

