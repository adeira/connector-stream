<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Application\StopStream;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\StopStreamEndpoint;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\SuccessResponse;
use Adeira\Connector\Stream\Infrastructure\Persistence\InMemoryAllStreams;
use Adeira\Connector\Stream\Stream;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

/**
 * @testCase
 */
final class StopStreamEndpointTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['identifier' => '00000000-0000-0000-0000-000000000001']);
		$repository = new InMemoryAllStreams;
		$repository->add(Stream::register(Uuid::fromString('00000000-0000-0000-0000-000000000001')));
		$newStreamCommand = new StopStream($repository);

		$endpoint = new StopStreamEndpoint($httpRequest, $newStreamCommand);
		Assert::type(SuccessResponse::class, $endpoint());
	}

	public function test_response_payload()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['identifier' => '00000000-0000-0000-0000-000000000001']);
		$repository = new InMemoryAllStreams;
		$repository->add(Stream::register(Uuid::fromString('00000000-0000-0000-0000-000000000001')));
		$newStreamCommand = new StopStream($repository);

		$endpoint = new StopStreamEndpoint($httpRequest, $newStreamCommand);
		Assert::same([
			'data' => [
				'identifier' => '00000000-0000-0000-0000-000000000001',
			],
		], $endpoint()->payload());
	}

	public function test_persistence()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['identifier' => '00000000-0000-0000-0000-000000000001']);
		$repository = new InMemoryAllStreams;
		$repository->add(Stream::register(Uuid::fromString('00000000-0000-0000-0000-000000000001')));
		$newStreamCommand = new StopStream($repository);
		$endpoint = new StopStreamEndpoint($httpRequest, $newStreamCommand);

		Assert::count(1, $data = $repository->fetchAll());
		Assert::type(Stream::class, reset($data));
		$endpoint(); // __invoke
		Assert::count(0, $repository->fetchAll());
	}

	public function test_that_missing_source_throws_exception()
	{
		$httpRequest = new Request(new UrlScript(''));
		$endpoint = new StopStreamEndpoint($httpRequest, NULL);
		Assert::exception(function () use ($endpoint) {
			$endpoint();
		}, PublicException::class, "POST body must contain 'identifier' field with stream identifier.");
	}

}

(new StopStreamEndpointTest)->run();
