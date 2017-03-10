<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Application\StartStream;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\StartStreamEndpoint;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\SuccessResponse;
use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\Stream;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

/**
 * @testCase
 */
final class StartStreamEndpointTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['source' => TRUE]);
		$repository = new InMemoryAllStreams;
		$newStreamCommand = new StartStream($repository);

		$endpoint = new StartStreamEndpoint($httpRequest, $newStreamCommand);
		Assert::type(SuccessResponse::class, $endpoint());
	}

	public function test_response_payload()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['source' => TRUE]);
		$repository = new InMemoryAllStreams;
		$newStreamCommand = new StartStream($repository);

		$endpoint = new StartStreamEndpoint($httpRequest, $newStreamCommand);
		Assert::same([
			'data' => [
				'source' => TRUE,
				'hls' => 'TODO',
			],
		], $endpoint()->payload());
	}

	public function test_persistence()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['source' => TRUE]);
		$repository = new InMemoryAllStreams;
		$newStreamCommand = new StartStream($repository);
		$endpoint = new StartStreamEndpoint($httpRequest, $newStreamCommand);

		Assert::count(0, $repository->fetchAll());
		$endpoint(); // __invoke
		Assert::count(1, $data = $repository->fetchAll());
		Assert::type(Stream::class, reset($data));
	}

	public function test_that_missing_source_throws_exception()
	{
		$httpRequest = new Request(new UrlScript(''));
		$endpoint = new StartStreamEndpoint($httpRequest, NULL);
		Assert::exception(function () use ($endpoint) {
			$endpoint();
		}, PublicException::class, "POST body must contain 'source' field with original stream destination.");
	}

}

(new StartStreamEndpointTest)->run();
