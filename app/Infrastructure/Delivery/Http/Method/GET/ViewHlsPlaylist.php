<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class ViewHlsPlaylist
{

	public function __invoke(string $identifier, string $file): IResponse
	{
		return new HlsResponse($identifier, $file);
	}

}
