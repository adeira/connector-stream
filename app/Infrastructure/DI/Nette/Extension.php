<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\DI\Nette;

use Nette\Http\IRequest;

final class Extension extends \Nette\DI\CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig([
			IRequest::GET => [],
			IRequest::POST => [],
		]);

		$counter = 0;
		foreach ($config as $httpMethod => $endpoints) {
			foreach ($endpoints as $endpoint) {
				$builder->addDefinition($this->prefix('endpoint_' . ++$counter))->setClass($endpoint);
			}
		}

		$builder
			->addDefinition($this->prefix('application'))
			->setClass(\Adeira\Connector\Stream\Infrastructure\Delivery\Http\Application::class)
			->setArguments([
				'routingTable' => $config,
			]);
	}

}
