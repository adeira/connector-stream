<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Persistence;

use Adeira\Connector\Stream\Stream;

final class InMemoryAllStreams implements \Adeira\Connector\Stream\IAllStreams
{

	private $memory = [];

	public function add(Stream $aStream): void
	{
		$this->memory[] = $aStream;
	}

	public function dumpMemory(): array
	{
		return $this->memory;
	}

}
