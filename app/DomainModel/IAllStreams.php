<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

use Ramsey\Uuid\UuidInterface;

interface IAllStreams
{

	public function add(Stream $aStream): void;

	public function remove(Stream $aStream): void;

	public function ofId(UuidInterface $uuid): ?Stream;

	/**
	 * @return \Adeira\Connector\Stream\Stream[]
	 */
	public function fetchAll(): array;

}
