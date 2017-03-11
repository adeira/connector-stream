<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Application\StartStream;
use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../../testsSetup.php';

/**
 * @testCase
 */
final class StartStreamTest extends \Tester\TestCase
{

	public function test_that_it_works()
	{
		$repository = new InMemoryAllStreams;
		Assert::same([], $repository->fetchAll());
		$command = new StartStream($repository);
		$command(Uuid::uuid4(), 'rtsp://a'); // __invoke

		$memory = $repository->fetchAll();
		Assert::count(1, $memory);
		Assert::type(Stream::class, reset($memory));
	}

}

(new StartStreamTest)->run();
