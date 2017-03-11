<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Application;

use Adeira\Connector\Stream\IAllStreams;
use Ramsey\Uuid\UuidInterface;

final class StopStream
{

	private $allStreams;

	public function __construct(IAllStreams $allStreams)
	{
		$this->allStreams = $allStreams;
	}

	public function __invoke(UuidInterface $streamIdentifier)
	{
		$stream = $this->allStreams->ofId($streamIdentifier);
		if ($stream === NULL) {
			throw new \Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException("Stream with identifier '$streamIdentifier' is not registered!");
		}
		$this->allStreams->remove($stream);
	}

}
