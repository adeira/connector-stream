<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\JsonResponse;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

/**
 * @testCase
 */
final class JsonResponseTest extends \Tester\TestCase
{

	public function test_that_emit_prints_html()
	{
		$expectedHtml = <<<HTML
<pre>{
    "a": true
}</pre>
HTML;
		$response = new JsonResponse(['a' => TRUE]);
		ob_start();
		$response->emit();
		Assert::same($expectedHtml, ob_get_clean());
	}

	public function test_pure_payload()
	{
		$response = new JsonResponse(['b' => FALSE]);
		Assert::same(['b' => FALSE], $response->payload());
	}

}

(new JsonResponseTest)->run();
