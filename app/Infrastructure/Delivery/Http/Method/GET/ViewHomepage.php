<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

use Adeira\Connector\Stream\IAllStreams;

final class ViewHomepage
{

	private $allStreams;

	public function __construct(IAllStreams $allStreams)
	{
		$this->allStreams = $allStreams;
	}

	public function __invoke(): IResponse
	{
		$payload = [];
		/** @var \Adeira\Connector\Stream\Stream $stream */
		foreach ($this->allStreams->fetchAll() as $stream) {
			$payload[] = [
				'id' => $stream->identifier()->toString(),
			];
		}
		return new JsonResponse($payload);
	}

}
