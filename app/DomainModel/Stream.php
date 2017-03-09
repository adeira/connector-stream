<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Stream
{

	private $identifier;

	public function __construct()
	{
		$this->identifier = Uuid::uuid4();
	}

	public function identifier(): UuidInterface
	{
		return $this->identifier;
	}

}
