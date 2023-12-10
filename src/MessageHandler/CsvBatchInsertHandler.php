<?php
namespace App\MessageHandler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Doctrine\DBAL\Connection;
use App\Message\CsvBatchInsertMessage;
use Pusher\Pusher;

class CsvBatchInsertHandler
{
    private Connection $connection;
    private Pusher $pusher;

    public function __construct(Connection $connection, Pusher $pusher)
    {
        $this->connection = $connection;
        $this->pusher = $pusher;
    }

    public function __invoke(CsvBatchInsertMessage $message)
    {
        $tableName = 'csv_data'; // Adjust this with your table name
        $data = $message->getData();

        $this->connection->beginTransaction();
        try {
            foreach ($data as $row) {
                $this->connection->insert($tableName, $row);
            }
            $this->connection->commit();

            $this->pusher->trigger('STATISTIC_LIST_REFRESH', 'STATISTIC_LIST_REFRESH', ['refetch' => true]);
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}