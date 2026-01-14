<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration adaptée pour MySQL
 */
final class Version20240315143823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Adaptation MySQL : backticks, suppression de la syntaxe Postgres
        $this->addSql('ALTER TABLE `book` ALTER `created_by_id` DROP DEFAULT');
        $this->addSql('ALTER TABLE `user` ADD `last_connected_at` DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // Repli adapté pour MySQL
        $this->addSql('ALTER TABLE `book` ALTER `created_by_id` SET DEFAULT 1');
        $this->addSql('ALTER TABLE `user` DROP COLUMN `last_connected_at`');
    }
}
