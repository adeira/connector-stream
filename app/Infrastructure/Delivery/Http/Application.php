<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

use Nette\DI;
use Nette\Http;

final class Application
{

	private $container;

	private $httpRequest;

	private $httpResponse;

	private $routingTable;

	public function __construct(DI\Container $container, Http\IRequest $httpRequest, Http\IResponse $httpResponse, array $routingTable)
	{
		$this->container = $container;
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
		$this->routingTable = $routingTable;
	}

	public function run(): void
	{
		$url = $this->httpRequest->getUrl();
		$slug = rtrim(substr($url->getPath(), strrpos($url->getScriptPath(), '/') + 1), '/');

		try {

			$params = $url->getQueryParameters();
			foreach ($this->routingTable[$this->httpRequest->getMethod()] as $staticSlug => $staticEndpoint) {
				if (preg_match('~^' . rtrim($staticSlug, '/') . '$~', $slug, $matches)) {
					foreach ($matches as $param => $value) {
						if (is_string($param)) { // named parameters in route mask
							$params[$param] = $value;
						}
					}
					$endpoint = $this->container->getByType($staticEndpoint, TRUE);
					$response = $this->invokeEndpoint($endpoint, $params);
					$response->emit();
					return;
				}
			}
			throw new \Adeira\Connector\Stream\Infrastructure\Delivery\PublicException(
				'Route not found for specified HTTP request.',
				Http\IResponse::S404_NOT_FOUND
			);

		} catch (\Adeira\Connector\Stream\Infrastructure\Delivery\PublicException $exc) {
			$this->httpResponse->setCode($exc->getCode());
			echo '<pre>' . json_encode((object)[
					'errors' => [
						$exc->getMessage(),
					],
				], JSON_PRETTY_PRINT) . '</pre>';
		}
	}

	private function invokeEndpoint($endpoint, array $queryParams)
	{
		$rm = new \ReflectionMethod($endpoint, '__invoke');

		foreach ($rm->getParameters() as $parameter) {
			if (!isset($queryParams[$parameter->getName()])) {
				throw new \Adeira\Connector\Stream\Infrastructure\Delivery\PublicException(
					'Route not found for specified HTTP request (wrong parameters).',
					Http\IResponse::S404_NOT_FOUND
				);
			}
		}

		return $rm->invokeArgs($endpoint, $queryParams);
	}

}
