<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

interface IAllStreams
{

	public function add(Stream $aStream): void;

	/**
	 * @return \Adeira\Connector\Stream\Stream[]
	 */
	public function fetchAll(): array;

}
