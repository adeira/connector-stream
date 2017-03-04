<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

use Nette\Http\IRequest;
use Nette\Http\IResponse;

final class Application
{

	private $staticRouting;

	private $request;

	private $response;

	public function __construct(array $staticRouting, IRequest $request, IResponse $response)
	{
		$this->staticRouting = $staticRouting;
		$this->request = $request;
		$this->response = $response;
	}

	public function run(): void
	{
		$url = $this->request->getUrl();
		$slug = rtrim(substr($url->getPath(), strrpos($url->getScriptPath(), '/') + 1), '/');

		foreach ($this->staticRouting as $staticSlug => $staticEndpoint) {
			if ($slug === rtrim($staticSlug, '/')) {
				echo '<pre>' . (new $staticEndpoint)() . '</pre>';
				return;
			}
		}

		$this->response->setCode(IResponse::S404_NOT_FOUND);
		echo '<pre>' . json_encode((object)[
				'errors' => [
					'Route not found for specified HTTP request.',
				],
			], JSON_PRETTY_PRINT) . '</pre>';
	}

}
