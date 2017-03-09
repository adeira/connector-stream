<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Stream
{

	private $identifier;

	private function __construct()
	{
	}

	public static function register(?UuidInterface $identifier = NULL): self
	{
		$stream = new self;
		$stream->identifier = $identifier ?: Uuid::uuid4();
		return $stream;
	}

	public function identifier(): UuidInterface
	{
		return $this->identifier;
	}

}
