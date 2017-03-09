<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Application;

use Adeira\Connector\Stream\{
	IAllStreams, Stream
};

final class CreateNewStream
{

	private $allStreams;

	public function __construct(IAllStreams $allStreams)
	{
		$this->allStreams = $allStreams;
	}

	public function __invoke()
	{
		$this->allStreams->add(new Stream);
	}

}
