<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class UserBandRepository
{
    const TABLE = 'users_bands';

    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function setUserAsAuthor($userId, $bandId)
    {
        $qb = new QueryBuilder($this->connection);

        $qb->update(self::TABLE, '')
            ->set('options', 1)
            ->where('user_id = :userId')
            ->andWhere('band_id = :bandId')
            ->setParameters(
                [
                    'userId' => $userId,
                    'bandId' => $bandId,
                ]
            )
            ->execute();
    }

    public function getOptionsByUserIdAndBandId($userId, $bandId)
    {
        $qb = new QueryBuilder($this->connection);

        return $qb->select('options')
            ->from(self::TABLE, '')
            ->where('user_id = :userId')
            ->andWhere('band_id = :bandId')
            ->setParameters(
                [
                    'userId' => $userId,
                    'bandId' => $bandId,
                ]
            )
            ->execute()
            ->fetchColumn();
    }

    public function getAuthorId($bandId)
    {
        $qb = new QueryBuilder($this->connection);

        return $qb->select('user_id')
            ->from(self::TABLE, '')
            ->where('options = 1')
            ->andWhere('band_id = :bandId')
            ->setParameters(
                [
                    'bandId' => $bandId,
                ]
            )
            ->execute()
            ->fetchColumn();
    }

    public function getBandsByAuthorId($authorId)
    {
        $qb = new QueryBuilder($this->connection);

        return $qb->select('band_id')
            ->from(self::TABLE, '')
            ->where('options = 1')
            ->andWhere('user_id = :authorId')
            ->setParameter('authorId', $authorId)
            ->execute()
            ->fetchAll();
    }
}
