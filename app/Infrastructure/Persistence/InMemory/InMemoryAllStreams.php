<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Persistence;

use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\UuidInterface;

final class InMemoryAllStreams implements \Adeira\Connector\Stream\IAllStreams
{

	private $memory = [];

	public function add(Stream $aStream): void
	{
		$this->memory[$aStream->identifier()->toString()] = $aStream;
	}

	public function remove(Stream $aStream): void
	{
		unset($this->memory[$aStream->identifier()->toString()]);
	}

	public function ofId(UuidInterface $uuid): ?Stream
	{
		return $this->memory[$uuid->toString()] ?? NULL;
	}

	public function fetchAll(): array
	{
		return $this->memory;
	}

}
