<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Stream
{

	private $identifier;

	private $rtspSource;

	private function __construct()
	{
	}

	public static function register(string $rtspSource, ?UuidInterface $identifier = NULL): self
	{
		$stream = new self;
		$stream->identifier = $identifier ?: Uuid::uuid4();
		$stream->rtspSource = $rtspSource;
		return $stream;
	}

	public function identifier(): UuidInterface
	{
		return $this->identifier;
	}

	public function rtspSource(): string
	{
		return $this->rtspSource;
	}

}
