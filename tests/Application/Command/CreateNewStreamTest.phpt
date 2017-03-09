<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Application\CreateNewStream;
use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\Stream;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class CreateNewStreamTest extends \Tester\TestCase
{

	public function test_that_it_works()
	{
		$repository = new InMemoryAllStreams;
		Assert::same([], $repository->fetchAll());
		$command = new CreateNewStream($repository);
		$command(); // __invoke

		$memory = $repository->fetchAll();
		Assert::count(1, $memory);
		Assert::type(Stream::class, reset($memory));
	}

}

(new CreateNewStreamTest)->run();
