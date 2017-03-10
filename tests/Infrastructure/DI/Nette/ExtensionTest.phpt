<?php declare(strict_types = 1);

use Tester\Assert;

/** @var Nette\DI\Container $dic */
$dic = require __DIR__ . '/../../../../bootstrap.php';
\Tester\Environment::setup();

Assert::noError(function () use ($dic) {
	$dic->getByType(\Adeira\Connector\Stream\Infrastructure\Delivery\Http\Application::class, TRUE);
});
