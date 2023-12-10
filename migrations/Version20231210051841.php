<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231210051841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE OR REPLACE VIEW statistics_view AS 
            SELECT 
                customer_id,
                COUNT(CASE WHEN num_region = ip_region THEN 1 END) AS total_call_same_continent,
                SUM(CASE WHEN num_region = ip_region THEN duration END) AS total_duration_same_continent,
                COUNT(*) AS total_call,
                SUM(duration) AS total_duration
            FROM csv_data
            GROUP BY customer_id;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP VIEW IF EXISTS statistics_view;');
    }
}
