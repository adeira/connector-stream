<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\SuccessResponse;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\ViewHomepage;
use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\Stream;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

/**
 * @testCase
 */
final class ViewHomepageTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$endpoint = new ViewHomepage(new InMemoryAllStreams);
		Assert::type(SuccessResponse::class, $endpoint());
	}

	public function test_response_payload()
	{
		$repository = new InMemoryAllStreams;
		$repository->add(Stream::register());
		$repository->add(Stream::register());
		$endpoint = new ViewHomepage($repository);

		$payload = $endpoint()->payload();
		Assert::count(2, $payload['data']);
		foreach ($payload['data'] as $item) {
			Assert::count(1, $item);
			Assert::same(1, preg_match('~[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}~i', $item['id']));
		}
	}

}

(new ViewHomepageTest)->run();
