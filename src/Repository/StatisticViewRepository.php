<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class StatisticViewRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getStatisticsData(int $page = 1, int $limit = 10)
    {
        // Fetch paginated data
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('statistics_view')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->execute();

        $data = $query->fetchAllAssociative();

        // Fetch total count of records
        $countQuery = $this->connection->createQueryBuilder()
            ->select('COUNT(*) as total_count')
            ->from('statistics_view');

        $totalCount = $countQuery->execute()->fetchOne();

        return [
            $data,
            $totalCount,
        ];
    }
}
