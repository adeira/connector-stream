<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\SuccessResponse;
use Nette\Http\Response;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

/**
 * @testCase
 */
final class SuccessResponseTest extends \Tester\TestCase
{

	public function test_that_emit_prints_html()
	{
		$expectedHtml = <<<HTML
{
    "data": {
        "a": true
    }
}
HTML;
		$response = new SuccessResponse(['a' => TRUE]);
		ob_start();
		$response->emit(new Response);
		Assert::same($expectedHtml, ob_get_clean());
	}

	public function test_pure_payload()
	{
		$response = new SuccessResponse(['b' => FALSE]);
		Assert::same([
			'data' => ['b' => FALSE],
		], $response->payload());
	}

	public function test_response_content_type()
	{
		$failure = new SuccessResponse(['c' => 'cc']);
		ob_start();
		$failure->emit($response = new class extends Response { // just a simple mock
			private $content = [];
			public function setContentType($type, $charset = NULL): void {
				$this->content = [$type, $charset];
			}
			public function getContentType(): array {
				return $this->content;
			}
		});
		ob_end_clean();
		Assert::same(['application/json', 'utf-8'], $response->getContentType());
	}

}

(new SuccessResponseTest)->run();
