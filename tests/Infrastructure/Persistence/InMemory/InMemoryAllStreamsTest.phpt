<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../../../testsSetup.php';

/**
 * @testCase
 */
final class InMemoryAllStreamsTest extends \Tester\TestCase
{

	public function test_that_add_works()
	{
		$repository = new InMemoryAllStreams;
		Assert::same([], $repository->fetchAll());
		$repository->add($stream = Stream::register('rtsp://a', $uuid = Uuid::uuid4()));
		Assert::equal([$stream], $repository->fetchAll());
	}

	public function test_that_remove_works()
	{
		$repository = new InMemoryAllStreams;
		$repository->add($stream = Stream::register('rtsp://b', $uuid = Uuid::uuid4()));
		Assert::equal([$stream], $repository->fetchAll());
		$repository->remove($repository->ofId($uuid));
		Assert::same([], $repository->fetchAll());
	}

}

(new InMemoryAllStreamsTest)->run();
