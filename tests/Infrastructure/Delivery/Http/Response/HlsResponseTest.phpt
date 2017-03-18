<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\HlsResponse;
use Nette\Http\Response;
use Tester\Assert;

require __DIR__ . '/../../../../testsSetup.php';

/**
 * @testCase
 */
final class HlsResponseTest extends \Tester\TestCase
{

	public function test_non_existent_directory()
	{
		Assert::exception(function () {
			new HlsResponse('aaa', '', '');
		}, \Exception::class, "Directory 'aaa' doesn't exist.");
	}

	public function test_non_existent_file()
	{
		$response = new HlsResponse(__DIR__, '', '');
		Assert::exception(function () use ($response) {
			$response->emit(new Response);
		}, \Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException::class, 'Stream file not found.');
	}

	public function test_playlist_response()
	{
		$response = new HlsResponse(__DIR__, '', 'stream.m3u8');
		ob_start();
		$response->emit(new Response);
		Assert::same("OK!\n", ob_get_clean());
	}

}

(new HlsResponseTest)->run();
