<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\HlsResponse;
use Nette\Http\Response;
use Tester\Assert;
use Tester\FileMock;

require __DIR__ . '/../../../../testsSetup.php';

/**
 * @testCase
 */
final class HlsResponseTest extends \Tester\TestCase
{

	public function test_exception()
	{
		$response = new HlsResponse('', '', '');
		Assert::exception(function () use ($response) {
			$response->emit(new Response);
		}, \Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException::class, 'Stream file not found.');
	}

}

(new HlsResponseTest)->run();
