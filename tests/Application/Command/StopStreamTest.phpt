<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Application\StopStream;
use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../../testsSetup.php';

/**
 * @testCase
 */
final class StopStreamTest extends \Tester\TestCase
{

	public function test_that_it_works()
	{
		$repository = new InMemoryAllStreams;
		Assert::count(0, $repository->fetchAll());
		$repository->add(Stream::register('rtsp://a', $id_1 = Uuid::uuid4()));
		Assert::count(1, $repository->fetchAll());
		$repository->add($stream_2 = Stream::register('rtsp://b', $id_2 = Uuid::uuid4()));
		Assert::count(2, $repository->fetchAll());

		$stop = new StopStream($repository);
		$stop($id_1); // __invoke

		Assert::count(1, $repository->fetchAll());
		Assert::equal([$stream_2], $repository->fetchAll());
	}

}

(new StopStreamTest)->run();
