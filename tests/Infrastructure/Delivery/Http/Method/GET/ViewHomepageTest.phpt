<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Application\GetStreamLocation;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\SuccessResponse;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\ViewHomepage;
use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\LocationFactory;
use Adeira\Connector\Stream\Stream;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Tester\Assert;

require __DIR__ . '/../../../../../testsSetup.php';

/**
 * @testCase
 */
final class ViewHomepageTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$endpoint = new ViewHomepage(new InMemoryAllStreams, NULL);
		Assert::type(SuccessResponse::class, $endpoint());
	}

	public function test_response_payload()
	{
		$repository = new InMemoryAllStreams;
		$repository->add(Stream::register('rtsp://a'));
		$repository->add(Stream::register('rtsp://b'));
		$endpoint = new ViewHomepage($repository, new GetStreamLocation($repository, new LocationFactory(new Request(new UrlScript))));

		$payload = $endpoint()->payload();
		Assert::count(2, $payload['data']);
		foreach ($payload['data'] as $item) {
			Assert::count(3, $item);
			Assert::same(1, preg_match('~[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}~i', $item['id']));
			Assert::true(array_key_exists('source', $item));
			Assert::true(array_key_exists('hls', $item));
		}
	}

}

(new ViewHomepageTest)->run();
