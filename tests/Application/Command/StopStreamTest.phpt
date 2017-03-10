<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Application\StopStream;
use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class StopStreamTest extends \Tester\TestCase
{

	public function test_that_it_works()
	{
		$repository = new InMemoryAllStreams;
		Assert::count(0, $repository->fetchAll());
		$repository->add(Stream::register($id_1 = Uuid::uuid4()));
		Assert::count(1, $repository->fetchAll());
		$repository->add(Stream::register($id_2 = Uuid::uuid4()));
		Assert::count(2, $repository->fetchAll());

		$stop = new StopStream($repository);
		$stop($id_1); // __invoke

		Assert::count(1, $repository->fetchAll());
		Assert::same($id_2->toString(), key($repository->fetchAll()));
	}

}

(new StopStreamTest)->run();
