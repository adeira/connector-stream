<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class HlsResponse implements IResponse
{

	private $streamsDirectory;

	private $identifier;

	private $file;

	public function __construct(string $streamsDirectory, string $identifier, string $file)
	{
		if (!is_dir($streamsDirectory)) {
			throw new \Exception("Directory '$streamsDirectory' doesn't exist.");
		}
		$this->streamsDirectory = $streamsDirectory;
		$this->identifier = $identifier;
		$this->file = $file;
	}

	public function emit(\Nette\Http\IResponse $httpResponse)
	{
		$filePath = realpath($this->streamsDirectory . "/$this->identifier/$this->file");

		if ($filePath !== FALSE && is_file($filePath)) {
			$httpResponse->setContentType('application/octet-stream');
			$httpResponse->setHeader('Content-Disposition', 'attachment');
			echo file_get_contents($filePath);
		} else {
			throw new PublicException('Stream file not found.');
		}
	}

}
