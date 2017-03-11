<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Application;

use Adeira\Connector\Stream\IAllStreams;
use Adeira\Connector\Stream\Location;
use Adeira\Connector\Stream\LocationFactory;
use Ramsey\Uuid\UuidInterface;

final class GetStreamLocation
{

	private $allStreams;

	private $locationFactory;

	public function __construct(IAllStreams $allStreams, LocationFactory $locationFactory)
	{
		$this->allStreams = $allStreams;
		$this->locationFactory = $locationFactory;
	}

	public function __invoke(UuidInterface $streamIdentifier): Location
	{
		$stream = $this->allStreams->ofId($streamIdentifier);
		return $this->locationFactory->createForStream($stream);
	}

}
