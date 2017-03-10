<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class SuccessResponse implements IResponse
{

	private $payload;

	public function __construct($payload)
	{
		$this->payload = ['data' => $payload ?: NULL];
	}

	public function emit(\Nette\Http\IResponse $httpResponse)
	{
		$httpResponse->setContentType('application/json', 'utf-8');
		echo json_encode($this->payload, JSON_PRETTY_PRINT);
	}

	public function payload(): array
	{
		return $this->payload;
	}

}
