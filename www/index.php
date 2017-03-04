<?php

$container = require __DIR__ . '/../bootstrap.php';

$container->getByType(\Adeira\Connector\Stream\Application::class)
	->run();
