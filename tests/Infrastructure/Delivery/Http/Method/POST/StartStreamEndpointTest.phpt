<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Application\GetStreamLocation;
use Adeira\Connector\Stream\Application\StartStream;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\StartStreamEndpoint;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\SuccessResponse;
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
final class StartStreamEndpointTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['source' => 'rtsp://a']);
		$repository = new InMemoryAllStreams;
		$newStreamCommand = new StartStream($repository);
		$getStreamLocation = new GetStreamLocation($repository, new LocationFactory($httpRequest));

		$endpoint = new StartStreamEndpoint($httpRequest, $newStreamCommand, $getStreamLocation);
		Assert::type(SuccessResponse::class, $endpoint());
	}

	public function test_response_payload()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['source' => 'rtsp://b']);
		$repository = new InMemoryAllStreams;
		$newStreamCommand = new StartStream($repository);
		$getStreamLocation = new GetStreamLocation($repository, new LocationFactory($httpRequest));

		$endpoint = new StartStreamEndpoint($httpRequest, $newStreamCommand, $getStreamLocation);
		$payload = $endpoint()->payload();
		Assert::count(3, $payload['data']);
		Assert::same('rtsp://b', $payload['data']['source']);
		Assert::same(1, preg_match('~^/hls/[a-zA-Z0-9]{22}/stream.m3u8$~', $payload['data']['hls']), $payload['data']['hls']);
		Assert::same(1, preg_match('~^[a-f0-9-]{36}$~i', $payload['data']['id']), $payload['data']['id']); // uuid
	}

	public function test_persistence()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['source' => 'rtsp://c']);
		$repository = new InMemoryAllStreams;
		$newStreamCommand = new StartStream($repository);
		$getStreamLocation = new GetStreamLocation($repository, new LocationFactory($httpRequest));
		$endpoint = new StartStreamEndpoint($httpRequest, $newStreamCommand, $getStreamLocation);

		Assert::count(0, $repository->fetchAll());
		$endpoint(); // __invoke
		Assert::count(1, $data = $repository->fetchAll());
		Assert::type(Stream::class, reset($data));
	}

	public function test_that_missing_source_throws_exception()
	{
		$httpRequest = new Request(new UrlScript(''));
		$repository = new InMemoryAllStreams;
		$getStreamLocation = new GetStreamLocation($repository, new LocationFactory($httpRequest));
		$endpoint = new StartStreamEndpoint($httpRequest, NULL, $getStreamLocation);
		Assert::exception(function () use ($endpoint) {
			$endpoint();
		}, PublicException::class, "POST body must contain 'source' field with original stream destination.");
	}

}

(new StartStreamEndpointTest)->run();
