<?php declare(strict_types = 1);

require __DIR__ . '/../../../../testsSetup.php';

/**
 * @testCase
 */
final class HlsResponseTest extends \Tester\TestCase
{

	public function test_skip()
	{
		\Tester\Environment::skip('HlsResponse is not ready yet.');
	}

}

(new HlsResponseTest)->run();
