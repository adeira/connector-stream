<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Persistence\SqlAllStreams;
use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../../../testsSetup.php';

/**
 * @testCase
 */
final class SqlAllStreamsTest extends \Tester\TestCase
{

	public function test_that_add_works()
	{
		$repository = new SqlAllStreams(new \PDO('sqlite::memory:'));
		Assert::same([], $repository->fetchAll());
		$repository->add($stream = Stream::register('rtsp://a', $uuid = Uuid::uuid4()));
		Assert::equal([$stream], $repository->fetchAll());
	}

	public function test_that_remove_works()
	{
		$repository = new SqlAllStreams(new \PDO('sqlite::memory:'));
		$repository->add($stream = Stream::register('rtsp://b', $uuid = Uuid::uuid4()));
		Assert::equal([$stream], $repository->fetchAll());
		$repository->remove($repository->ofId($uuid));
		Assert::same([], $repository->fetchAll());
	}

}

(new SqlAllStreamsTest)->run();
