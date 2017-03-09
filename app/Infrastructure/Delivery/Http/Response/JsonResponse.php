<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class JsonResponse implements IResponse
{

	private $payload;

	public function __construct($payload)
	{
		$this->payload = $payload;
	}

	public function emit()
	{
		echo '<pre>' . json_encode($this->payload, JSON_PRETTY_PRINT) . '</pre>';
	}

	public function payload()
	{
		return $this->payload;
	}

}
