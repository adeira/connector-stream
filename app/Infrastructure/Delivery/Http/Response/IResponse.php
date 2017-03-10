<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

interface IResponse
{

	public function emit(\Nette\Http\IResponse $httpResponse);

}
