<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery;

final class ViewHlsPlaylist
{

	public function __invoke(string $identifier, string $file): IResponse
	{
		//FIXME: autorizace?
		return new HlsResponse($identifier, $file);
	}

}
