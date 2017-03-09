<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\ConsumeStream;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\JsonResponse;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

/**
 * @testCase
 */
final class ConsumeStreamTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['source' => TRUE]);
		$endpoint = new ConsumeStream($httpRequest);
		Assert::type(JsonResponse::class, $endpoint());
	}

	public function test_response_payload()
	{
		$httpRequest = new Request(new UrlScript(''), NULL, ['source' => TRUE]);
		$endpoint = new ConsumeStream($httpRequest);
		Assert::same([
			'source' => TRUE,
			'hls' => 'TODO',
		], (array)$endpoint()->payload());
	}

	public function test_that_missing_source_throws_exception()
	{
		$httpRequest = new Request(new UrlScript(''));
		$endpoint = new ConsumeStream($httpRequest);
		Assert::exception(function () use ($endpoint) {
			$endpoint();
		}, PublicException::class, "POST body must contain 'source' field with original stream destination.");
	}

}

(new ConsumeStreamTest)->run();
