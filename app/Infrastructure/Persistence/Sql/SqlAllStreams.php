<?php declare(strict_types = 1);

namespace Adeira\Connector\Stream\Infrastructure\Persistence;

use Adeira\Connector\Stream\Stream;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class SqlAllStreams implements \Adeira\Connector\Stream\IAllStreams
{

	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;

		//FIXME: lazy (do not call it in the constructor)
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->pdo->exec('
			CREATE TABLE IF NOT EXISTS streams (
				uuid TEXT NOT NULL
			);
			CREATE INDEX IF NOT EXISTS idx_streams_uuid ON streams(uuid);
			CREATE UNIQUE INDEX IF NOT EXISTS uidx_streams_uuid ON streams(uuid);
		');
	}

	public function add(Stream $aStream): void
	{
		$statement = $this->pdo->prepare('INSERT INTO streams (uuid) VALUES (:uuid)');
		$statement->execute([':uuid' => $aStream->identifier()]);
	}

	public function remove(Stream $aStream): void
	{
		$statement = $this->pdo->prepare('DELETE FROM streams WHERE uuid = :uuid');
		$statement->execute([':uuid' => $aStream->identifier()]);
	}

	public function ofId(UuidInterface $uuid): ?Stream
	{
		$statement = $this->pdo->prepare('SELECT * FROM streams WHERE uuid = :uuid');
		$statement->execute([':uuid' => $uuid->toString()]);

		return $this->fillUpEntity($statement->fetch(\PDO::FETCH_ASSOC));
	}

	public function fetchAll(): array
	{
		$statement = $this->pdo->prepare('SELECT * FROM streams');
		$statement->execute();

		$result = [];
		foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
			$result[] = $this->fillUpEntity($row);
		}
		return $result;
	}

	/**
	 * It instantiate entity without calling constructor and fills up properties like Doctrine do.
	 */
	private function fillUpEntity(array $row): Stream
	{
		$instantiator = new \Doctrine\Instantiator\Instantiator;
		$hydratorClass = (new \GeneratedHydrator\Configuration(Stream::class))->createFactory()->getHydratorClass();
		return (new $hydratorClass)->hydrate([
			'identifier' => Uuid::fromString($row['uuid']),
		], $instantiator->instantiate(Stream::class));
	}

}
