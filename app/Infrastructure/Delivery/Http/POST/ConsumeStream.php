<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery;

use Nette\Http\IRequest;

final class ConsumeStream
{

	private $httpRequest;

	public function __construct(IRequest $httpRequest)
	{
		$this->httpRequest = $httpRequest;
	}

	public function __invoke(): IResponse
	{
		$source = $this->httpRequest->getPost('source');
		if (!$source) {
			throw new PublicException("POST body must contain 'source' field with original stream destination.");
		}
		return new JsonResponse((object)[
			'source' => $source,
			'hls' => 'TODO', //TODO
		]);
	}

}
