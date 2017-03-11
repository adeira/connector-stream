<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

use Adeira\Connector\Stream\Application\StartStream;
use Adeira\Connector\Stream\Application\GetStreamLocation;
use Nette\Http\IRequest;
use Ramsey\Uuid\Uuid;

final class StartStreamEndpoint
{

	private $httpRequest;

	private $startStream;

	private $getStreamLocation;

	public function __construct(?IRequest $httpRequest, ?StartStream $startStream, GetStreamLocation $getStreamLocation)
	{
		$this->httpRequest = $httpRequest;
		$this->startStream = $startStream;
		$this->getStreamLocation = $getStreamLocation;
	}

	public function __invoke(): IResponse
	{
		$source = $this->httpRequest->getPost('source');
		if (!$source) {
			throw new PublicException("POST body must contain 'source' field with original stream destination.");
		}

		$identifier = Uuid::uuid4();
		$this->startStream->__invoke($identifier, $source);

		$location = $this->getStreamLocation->__invoke($identifier);
		return new SuccessResponse([
			'id' => $identifier->toString(),
			'source' => $source,
			'hls' => $location->playlistPublicPath(),
		]);
	}

}
