<?php

require __DIR__ . '/vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode(getenv('NETTE_DEBUG') === '1');
$configurator->enableTracy(__DIR__ . '/var/log');

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/var/temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/app')
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$requestFactory = new Nette\Http\RequestFactory;
$requestFactory->urlFilters = [
	'path' => [
		'~/{2,}~' => '/', // clean the path from duplicated slashes
	],
	'url' => [
		'~[.,)]$~' => '', // remove dot, comma or right parenthesis form the end of the URL
	],
];
$configurator->addServices([
	'http.request' => $requestFactory->createHttpRequest(),
]);

$configurator->addDynamicParameters([
	'rootDir' => __DIR__,
]);

return $configurator->createContainer();
