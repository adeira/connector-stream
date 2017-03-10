<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\FailureResponse;
use Nette\Http\Response;
use Tester\Assert;

require __DIR__ . '/../../../../../bootstrap.php';

/**
 * @testCase
 */
final class FailureResponseTest extends \Tester\TestCase
{

	public function test_that_emit_prints_html()
	{
		$expectedHtml = <<<HTML
{
    "errors": [
        {
            "message": "error message"
        }
    ]
}
HTML;
		$response = new FailureResponse(['error message']);
		ob_start();
		$response->emit(new Response);
		Assert::same($expectedHtml, ob_get_clean());
	}

	public function test_that_empty_messages_throws_exception()
	{
		Assert::exception(function () {
			new FailureResponse([]);
		}, \Exception::class, 'Error messages cannot be empty.');
	}

	public function test_response_content_type()
	{
		$failure = new FailureResponse(['error message']);
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

(new FailureResponseTest)->run();
