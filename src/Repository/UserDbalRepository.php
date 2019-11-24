<?php


namespace App\Repository;


use Doctrine\DBAL\Connection;

class UserDbalRepository
{
    const TABLE = 'users';

    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function userExists($username)
    {
        $username = '"' . $username . '"';
        $sql = sprintf('SELECT username FROM users WHERE username like %s', $username);
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}