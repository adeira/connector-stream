<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class StreamTest extends \Tester\TestCase
{

	public function test_that_identifier_is_uuid()
	{
		$stream = new Stream;
		Assert::type(Uuid::class, $stream->id());
		Assert::same(4, $stream->id()->getVersion());
	}

}

(new StreamTest)->run();
