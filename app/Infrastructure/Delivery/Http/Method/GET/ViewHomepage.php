<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class ViewHomepage
{

	public function __invoke(): IResponse
	{
		//TODO: print allowed streams
		return new JsonResponse(['ok']);
	}

}
