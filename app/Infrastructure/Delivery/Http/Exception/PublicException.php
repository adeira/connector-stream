<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Delivery\Http;

final class PublicException extends \Exception
{

	public function __construct($message = "", $code = \Nette\Http\IResponse::S500_INTERNAL_SERVER_ERROR, \Exception $previous = NULL)
	{
		parent::__construct($message, $code, $previous);
	}

}
