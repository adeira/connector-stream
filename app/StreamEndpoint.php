<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

final class StreamEndpoint
{

	public function __invoke()
	{
		return json_encode((object)['status' => 'ok'], JSON_PRETTY_PRINT);
	}

}
