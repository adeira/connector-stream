<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Location;
use Adeira\Connector\Stream\Stream;
use Nette\Http\Url;
use Ramsey\Uuid\Uuid;
use Tester\Assert;

require __DIR__ . '/../testsSetup.php';

/**
 * @testCase
 */
final class LocationTest extends \Tester\TestCase
{

	public function test_playlistPublicPath()
	{
		$location = new Location(
			new Url('http://x.y'),
			Stream::register('rtsp://a', Uuid::fromString('e9a3970a-2fe4-44ab-8f02-f2378bb8bbce'))
		);
		Assert::same('/hls/LCYin5no67JFqVfinbiTaj/stream.m3u8', $location->playlistPublicPath());
	}

	public function test_directory()
	{
		$location = new Location(
			new Url('http://x.y'),
			Stream::register('rtsp://a', Uuid::fromString('e9a3970a-2fe4-44ab-8f02-f2378bb8bbce'))
		);
		Assert::same('LCYin5no67JFqVfinbiTaj', $location->directory());
	}

}

(new LocationTest)->run();
