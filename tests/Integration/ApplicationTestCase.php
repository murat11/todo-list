<?php declare(strict_types=1);

namespace Test\Integration;

use App\Infrastructure\Repository\TodoListDbalRepository;
use App\Infrastructure\Repository\IdGenerator\UuidGenerator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PHPUnit\Framework\TestCase;

abstract class ApplicationTestCase extends TestCase
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var TodoListDbalRepository
     */
    protected $repository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->connection = DriverManager::getConnection(['url' => 'mysql://root@mysql-test:3306/app_test']);
        $this->repository = new TodoListDbalRepository($this->connection, new UuidGenerator());
    }

    protected function setUp(): void
    {
        $this->connection->executeQuery('TRUNCATE TABLE `todo_list`');
    }
}
