<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

use Nette\Http\IRequest;

final class LocationFactory
{

	private $httpRequest;

	public function __construct(IRequest $httpRequest)
	{
		$this->httpRequest = $httpRequest;
	}

	public function createForStream(Stream $stream): Location
	{
		return new Location($this->httpRequest->getUrl(), $stream);
	}

}
