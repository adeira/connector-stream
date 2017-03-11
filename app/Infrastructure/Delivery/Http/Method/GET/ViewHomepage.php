<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

use Adeira\Connector\Stream\Application\GetStreamLocation;
use Adeira\Connector\Stream\IAllStreams;

final class ViewHomepage
{

	private $allStreams;

	private $getStreamLocation;

	public function __construct(IAllStreams $allStreams, ?GetStreamLocation $getStreamLocation)
	{
		$this->allStreams = $allStreams;
		$this->getStreamLocation = $getStreamLocation;
	}

	public function __invoke(): IResponse
	{
		$payload = [];
		/** @var \Adeira\Connector\Stream\Stream $stream */
		foreach ($this->allStreams->fetchAll() as $stream) {
			$streamLocation = $this->getStreamLocation->__invoke($stream->identifier());
			$payload[] = [
				'id' => $stream->identifier()->toString(),
				'source' => $stream->rtspSource(),
				'hls' => $streamLocation->playlistPublicPath(),
			];
		}
		return new SuccessResponse($payload);
	}

}
