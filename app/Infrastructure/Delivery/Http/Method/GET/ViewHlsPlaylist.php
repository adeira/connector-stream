<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class ViewHlsPlaylist
{

	/**
	 * @var string
	 */
	private $streamDir;

	public function __construct(string $streamDir)
	{
		$this->streamDir = $streamDir;
	}

	public function __invoke(string $identifier, string $file): IResponse
	{
		return new HlsResponse($this->streamDir, $identifier, $file);
	}

}
