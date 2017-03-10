<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\HlsResponse;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\ViewHlsPlaylist;
use Tester\Assert;

require __DIR__ . '/../../../../../testsSetup.php';

/**
 * @testCase
 */
final class ViewHlsPlaylistTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$endpoint = new ViewHlsPlaylist;
		Assert::type(HlsResponse::class, $endpoint('device1', 'stream.m3u8'));
	}

	public function test_response_payload()
	{
		\Tester\Environment::skip('HlsResponse is not ready yet.');

		$endpoint = new ViewHlsPlaylist;
		$response = $endpoint('device1', 'stream.m3u8');
		Assert::same([], $response->emit());
	}

}

(new ViewHlsPlaylistTest)->run();
