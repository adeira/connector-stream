<?php declare(strict_types = 1);

use Adeira\Connector\Stream\Infrastructure\Delivery\Http\Application;
use Adeira\Connector\Stream\Infrastructure\Delivery\Http\SuccessResponse;
use Nette\Http\Request;
use Nette\Http\Response;
use Nette\Http\UrlScript;
use Tester\Assert;

require __DIR__ . '/../../../testsSetup.php';

/**
 * @testCase
 */
final class ApplicationTest extends \Tester\TestCase
{

	public function test_missing_endpoint()
	{
		ob_start();
		$code = $this->runApplication();
		Assert::same(['errors' => [['message' => 'Route not found for specified HTTP request.']]], json_decode(ob_get_clean(), TRUE));
		Assert::same(404, $code);
	}

	public function test_success_endpoint()
	{
		ob_start();
		$code = $this->runApplication('/success');
		Assert::same(['data' => 'success'], json_decode(ob_get_clean(), TRUE));
		Assert::same(200, $code);

		ob_start();
		$code = $this->runApplication('/success', ['optional' => 'text']);
		Assert::same(['data' => 'text'], json_decode(ob_get_clean(), TRUE));
		Assert::same(200, $code);
	}

	public function test_success_endpoint_with_parameters()
	{
		ob_start();
		$code = $this->runApplication('/successParams');
		Assert::same(['errors' => [['message' => "Missing parameter 'string'."]]], json_decode(ob_get_clean(), TRUE));
		Assert::same(500, $code);

		ob_start();
		$code = $this->runApplication('/successParams', ['string' => 1]);
		Assert::same(['errors' => [['message' => "Missing parameter 'int'."]]], json_decode(ob_get_clean(), TRUE));
		Assert::same(500, $code);

		ob_start();
		$code = $this->runApplication('/successParams', ['string' => 1, 'int' => 2]);
		Assert::same(['data' => '1:2'], json_decode(ob_get_clean(), TRUE));
		Assert::same(200, $code);
	}

	public function test_public_exception()
	{
		ob_start();
		$code = $this->runApplication('/publicException');
		Assert::same(['errors' => [['message' => 'message']]], json_decode(ob_get_clean(), TRUE));
		Assert::same(500, $code);
	}

	public function test_internal_exception()
	{
		ob_start();
		$code = $this->runApplication('/internalException');
		Assert::same(['errors' => [['message' => 'Internal Server Error']]], json_decode(ob_get_clean(), TRUE));
		Assert::same(500, $code);
	}

	private function runApplication($path = '/', $query = NULL): int
	{
		if (!class_exists(InMemoryDic::class)) {
			$builder = new \Nette\DI\ContainerBuilder;

			$builder->addDefinition('a')->setClass(GetSuccessEndpoint::class);
			$builder->addDefinition('b')->setClass(GetSuccessEndpointWithParameters::class);
			$builder->addDefinition('c')->setClass(PublicException::class);
			$builder->addDefinition('d')->setClass(InternalException::class);

			$dicCode = implode('', (new Nette\DI\PhpGenerator($builder))->generate('InMemoryDic'));
			eval($dicCode);
		}

		$app = new Application(
			new InMemoryDic,
			new Request((new UrlScript("https://x.y$path"))->setQuery($query)),
			$response = new Response,
			[
				'GET' => [
					'success' => GetSuccessEndpoint::class,
					'successParams' => GetSuccessEndpointWithParameters::class,
					'publicException' => PublicException::class,
					'internalException' => InternalException::class,
				],
				'POST' => [], // TODO
			]
		);
		$app->run();

		return $response->getCode();
	}

}

(new ApplicationTest)->run();

final class GetSuccessEndpoint {
	public function __invoke(string $optional = NULL) {
		return new SuccessResponse($optional ?: 'success');
	}
}

final class GetSuccessEndpointWithParameters {
	public function __invoke(string $string, int $int) {
		return new SuccessResponse($string . ':' . $int);
	}
}

final class PublicException {
	public function __invoke() {
		throw new \Adeira\Connector\Stream\Infrastructure\Delivery\Http\PublicException('message');
	}
}

final class InternalException {
	public function __invoke() {
		throw new \Exception('internal');
	}
}
