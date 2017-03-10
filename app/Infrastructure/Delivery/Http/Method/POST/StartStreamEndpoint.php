<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

use Adeira\Connector\Stream\Application\StartStream;
use Nette\Http\IRequest;
use Ramsey\Uuid\Uuid;

final class StartStreamEndpoint
{

	private $httpRequest;

	private $startStream;

	public function __construct(?IRequest $httpRequest, ?StartStream $startStream)
	{
		$this->httpRequest = $httpRequest;
		$this->startStream = $startStream;
	}

	public function __invoke(): IResponse
	{
		$source = $this->httpRequest->getPost('source');
		if (!$source) {
			throw new PublicException("POST body must contain 'source' field with original stream destination.");
		}

		$identifier = Uuid::uuid4();
		$this->startStream->__invoke($identifier);

		return new SuccessResponse([
			'source' => $source,
			'hls' => 'TODO', //TODO
		]);
	}

}
