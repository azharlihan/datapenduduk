<?php

// Change root directory to init.php location
chdir(__DIR__);

// Load the configuration
require_once 'core/Config.php';

// Load the real app class
require_once 'core/App.php';

// Autoload another core class
spl_autoload_register(function ($className) {
	if (file_exists("core/$className.php")) {
		require_once "core/$className.php";
	}
});

// Autoload controller class
spl_autoload_register(function ($className) {
	if (file_exists("controllers/$className.php")) {
		require_once "controllers/$className.php";
	}
});

// Instantiation app
$app = new App;
