<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Application;

use Adeira\Connector\Stream\{
	IAllStreams, Stream
};
use Ramsey\Uuid\UuidInterface;

final class StartStream
{

	private $allStreams;

	public function __construct(IAllStreams $allStreams)
	{
		$this->allStreams = $allStreams;
	}

	public function __invoke(UuidInterface $streamIdentifier)
	{
		//TODO: transakce
		$registeredStream = Stream::register($streamIdentifier);
		$this->allStreams->add($registeredStream);
	}

}
