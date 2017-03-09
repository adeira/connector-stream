<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\JsonResponse;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\ViewHomepage;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

/**
 * @testCase
 */
final class ViewHomepageTest extends \Tester\TestCase
{

	public function test_response_type()
	{
		$endpoint = new ViewHomepage;
		Assert::type(JsonResponse::class, $endpoint());
	}

	public function test_response_payload()
	{
		$endpoint = new ViewHomepage;
		Assert::same(['ok'], $endpoint()->payload());
	}

}

(new ViewHomepageTest)->run();
