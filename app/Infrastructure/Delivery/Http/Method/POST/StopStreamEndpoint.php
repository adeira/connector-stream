<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

use Adeira\Connector\Stream\Application\StopStream;
use Nette\Http\IRequest;
use Ramsey\Uuid\Uuid;

final class StopStreamEndpoint
{

	private $httpRequest;

	/**
	 * @var \Adeira\Connector\Stream\Application\StopStream|null
	 */
	private $stopStream;

	public function __construct(?IRequest $httpRequest, ?StopStream $stopStream)
	{
		$this->httpRequest = $httpRequest;
		$this->stopStream = $stopStream;
	}

	public function __invoke()
	{
		$identifier = $this->httpRequest->getPost('identifier');
		if (!$identifier) {
			throw new PublicException("POST body must contain 'identifier' field with stream identifier.");
		}

		try {
			$uuid = Uuid::fromString($identifier);
		} catch (\InvalidArgumentException $exc) {
			throw new PublicException('Identifier must be in valid UUID format version 4.');
		}
		$this->stopStream->__invoke($uuid);

		return new SuccessResponse([
			'identifier' => $identifier,
		]);
	}

}
