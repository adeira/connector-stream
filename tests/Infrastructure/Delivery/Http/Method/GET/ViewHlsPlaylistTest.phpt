<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\HlsResponse;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\ViewHlsPlaylist;
use Nette\Http\Response;
use Tester\Assert;

require __DIR__ . '/../../../../../testsSetup.php';

/**
 * @testCase
 */
final class ViewHlsPlaylistTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$endpoint = new ViewHlsPlaylist('streamDir');
		Assert::type(HlsResponse::class, $endpoint('device1', 'stream.m3u8'));
	}

	public function test_response_exception_payload()
	{
		$endpoint = new ViewHlsPlaylist('streamDir');
		Assert::exception(function() use ($endpoint) {
			$response = $endpoint('device1', 'stream.m3u8');
			$response->emit(new Response);
		}, \Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException::class, 'Stream file not found.');
	}

}

(new ViewHlsPlaylistTest)->run();
