<?php

$container = require __DIR__ . '/../bootstrap.php';

$container->getByType(\Adeira\Connector\Stream\Infrastructure\Delivery\Http\Application::class)
	->run();
