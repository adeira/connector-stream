<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

/**
 * @testCase
 */
final class PublicExceptionTest extends \Tester\TestCase
{

	public function test_that_exception_message_works()
	{
		Assert::same('aaa', (new PublicException('aaa'))->getMessage());
		Assert::same('bbb', (new PublicException('bbb'))->getMessage());
	}

	public function test_that_exception_code_works()
	{
		Assert::same(404, (new PublicException('', 404))->getCode());
		Assert::same(500, (new PublicException('', 500))->getCode());
	}

}

(new PublicExceptionTest)->run();
