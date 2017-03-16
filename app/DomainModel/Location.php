<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

use Nette\Http\Url;
use PascalDeVink\ShortUuid\ShortUuid;

final class Location
{

	/**
	 * @var \Nette\Http\Url
	 */
	private $url;

	/**
	 * @var \Adeira\Connector\Stream\Stream
	 */
	private $stream;

	public function __construct(Url $url, Stream $stream)
	{
		$this->url = $url;
		$this->stream = $stream;
	}

	public function playlistPublicPath(): string
	{
		return $this->basePath() . '/stream.m3u8';
	}

	public function directory(): string
	{
		$shortUuid = new ShortUuid;
		return $shortUuid->encode($this->stream->identifier());
	}

	private function basePath(): string
	{
		return '/hls/' . $this->directory();
	}

}
