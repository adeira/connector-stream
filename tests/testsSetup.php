<?php

require __DIR__ . '/../vendor/autoload.php';

$loader = new Nette\Loaders\RobotLoader;
$loader->setCacheStorage(new Nette\Caching\Storages\MemoryStorage);
$loader->addDirectory(__DIR__ . '/../app');
$loader->register();

\Tracy\Debugger::setLogger(new class implements \Tracy\ILogger
{

	public function log($value, $priority = self::INFO)
	{
		// null
	}

});

\Tester\Environment::setup();
