<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../testsSetup.php';

/**
 * @testCase
 */
final class StreamTest extends \Tester\TestCase
{

	public function test_that_identifier_is_uuid()
	{
		$stream = Stream::register('rtsp://a');
		Assert::type(Uuid::class, $stream->identifier());
		Assert::same(4, $stream->identifier()->getVersion());
	}

	public function test_register_method()
	{
		$stream = Stream::register('rtsp://b', Uuid::fromString('00000000-0000-0000-0000-000000000001'));
		Assert::type(Uuid::class, $stream->identifier());
		Assert::null($stream->identifier()->getVersion());
		Assert::same('00000000-0000-0000-0000-000000000001', $stream->identifier()->toString());
	}

}

(new StreamTest)->run();
