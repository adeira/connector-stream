<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery;

final class HlsResponse implements IResponse
{

	private $identifier;

	private $file;

	public function __construct(string $identifier, string $file)
	{
		$this->identifier = $identifier;
		$this->file = $file;
	}

	public function emit()
	{
		header('Content-Disposition: attachment'); //FIXME
		echo file_get_contents(__DIR__ . "/../../../../../streams/$this->identifier/$this->file"); //FIXME: nemusí existovat
	}

}
