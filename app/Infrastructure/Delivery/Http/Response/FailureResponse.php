<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class FailureResponse implements IResponse
{

	private $payload;

	public function __construct(array $errorMessages)
	{
		if (!$errorMessages) {
			throw new \Exception('Error messages cannot be empty.');
		}

		$errors = [];
		foreach ($errorMessages as $message) {
			$errors[] = ['message' => $message];
		}
		$this->payload = ['errors' => $errors];
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
