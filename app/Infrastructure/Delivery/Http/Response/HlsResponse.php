<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class HlsResponse implements IResponse
{

	private $identifier;

	private $file;

	public function __construct(string $identifier, string $file)
	{
		$this->identifier = $identifier;
		$this->file = $file;
	}

	public function emit(\Nette\Http\IResponse $httpResponse)
	{
		$filePath = __DIR__ . "/../../../../../streams/$this->identifier/$this->file"; //FIXME

		if (file_exists($filePath)) {
			$httpResponse->setContentType('application/octet-stream');
			$httpResponse->setHeader('Content-Disposition', 'attachment');
			echo file_get_contents($filePath);
		}

		throw new PublicException('Stream playlist not found.');
	}

}
