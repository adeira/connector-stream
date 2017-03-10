<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

use Nette\DI;
use Nette\Http;

final class Application
{

	private $container;

	private $httpRequest;

	private $httpResponse;

	private $routingTable;

	public function __construct(DI\Container $container, Http\IRequest $httpRequest, Http\IResponse $httpResponse, array $routingTable)
	{
		$this->container = $container;
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
		$this->routingTable = $routingTable;
	}

	public function run(): void
	{
		$url = $this->httpRequest->getUrl();
		$slug = rtrim(substr($url->getPath(), strrpos($url->getScriptPath(), '/') + 1), '/');

		try {

			$params = $url->getQueryParameters();
			foreach ($this->routingTable[$this->httpRequest->getMethod()] as $staticSlug => $staticEndpoint) {
				if (preg_match('~^' . rtrim($staticSlug, '/') . '$~', $slug, $matches)) {
					foreach ($matches as $param => $value) {
						if (is_string($param)) { // named parameters in route mask
							$params[$param] = $value;
						}
					}
					$endpoint = $this->container->getByType($staticEndpoint, TRUE);
					$response = $this->invokeEndpoint($endpoint, $params);
					$response->emit($this->httpResponse);
					return;
				}
			}
			throw new PublicException(
				'Route not found for specified HTTP request.',
				Http\IResponse::S404_NOT_FOUND
			);

		} catch (PublicException $exc) {

			$this->httpResponse->setCode($exc->getCode() ?: Http\IResponse::S500_INTERNAL_SERVER_ERROR);
			(new FailureResponse([$exc->getMessage()]))->emit($this->httpResponse);

		} catch (\Exception $exc) {

			\Tracy\Debugger::log($exc, \Tracy\Debugger::EXCEPTION);
			$this->httpResponse->setCode(Http\IResponse::S500_INTERNAL_SERVER_ERROR);
			(new FailureResponse(['Internal Server Error']))->emit($this->httpResponse);

		}
	}

	private function invokeEndpoint($endpoint, array $queryParams)
	{
		$rm = new \ReflectionMethod($endpoint, '__invoke');
		$arguments = self::combineArgs($rm, $queryParams);
		return $rm->invokeArgs($endpoint, $arguments);
	}

	/**
	 * @see Nette\Application\UI\ComponentReflection
	 */
	private static function combineArgs(\ReflectionFunctionAbstract $method, $args): array
	{
		$res = [];
		foreach ($method->getParameters() as $i => $param) {
			$name = $param->getName();
			[$type, $isClass] = self::getParameterType($param);
			if (isset($args[$name])) {
				$res[$i] = $args[$name];
				if (!self::convertType($res[$i], $type, $isClass)) {
					throw new \Nette\InvalidArgumentException(sprintf(
						'Argument $%s passed to %s() must be %s, %s given.',
						$name,
						($method instanceof \ReflectionMethod ? $method->getDeclaringClass()->getName() . '::' : '') . $method->getName(),
						$type === 'NULL' ? 'scalar' : $type,
						is_object($args[$name]) ? get_class($args[$name]) : gettype($args[$name])
					));
				}
			} elseif ($param->isDefaultValueAvailable()) {
				$res[$i] = $param->getDefaultValue();
			} elseif ($type === 'NULL' || $param->allowsNull()) {
				$res[$i] = NULL;
			} elseif ($type === 'array') {
				$res[$i] = [];
			} else {
				throw new \Nette\InvalidArgumentException(sprintf(
					'Missing parameter $%s required by %s()',
					$name,
					($method instanceof \ReflectionMethod ? $method->getDeclaringClass()->getName() . '::' : '') . $method->getName()
				));
			}
		}
		return $res;
	}

	/**
	 * @see Nette\Application\UI\ComponentReflection
	 */
	private static function convertType(&$val, string $type, bool $isClass = FALSE): bool
	{
		if ($isClass) {
			return $val instanceof $type;
		} elseif ($type === 'callable') {
			return FALSE;
		} elseif ($type === 'NULL') { // means 'not array'
			return !is_array($val);
		} elseif ($type === 'array') {
			return is_array($val);
		} elseif (!is_scalar($val)) { // array, resource, NULL, etc.
			return FALSE;
		} else {
			$old = $tmp = ($val === FALSE ? '0' : (string)$val);
			settype($tmp, $type);
			if ($old !== ($tmp === FALSE ? '0' : (string)$tmp)) {
				return FALSE; // data-loss occurs
			}
			$val = $tmp;
		}
		return TRUE;
	}

	/**
	 * @see Nette\Application\UI\ComponentReflection
	 */
	private static function getParameterType(\ReflectionParameter $param): array
	{
		return $param->hasType()
			? [(string)$param->getType(), !$param->getType()->isBuiltin()]
			: [gettype($param->isDefaultValueAvailable() ? $param->getDefaultValue() : NULL), FALSE];
	}

}
