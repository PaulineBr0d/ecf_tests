<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration adaptée pour MySQL
 */
final class Version20240223144306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adapted migration for MySQL (no sequences, no pg_notify)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `editor` (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `book` (id INT NOT NULL AUTO_INCREMENT, editor_id INT NOT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, cover VARCHAR(255) NOT NULL, edited_at DATETIME NOT NULL, plot TEXT NOT NULL, page_number INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE INDEX IDX_CBE5A3316995AC4C ON `book` (`editor_id`)');
        $this->addSql('ALTER TABLE `book` ADD CONSTRAINT `FK_CBE5A3316995AC4C` FOREIGN KEY (`editor_id`) REFERENCES `editor` (`id`)');

        $this->addSql('CREATE TABLE `messenger_messages` (id BIGINT NOT NULL AUTO_INCREMENT, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON `messenger_messages` (`queue_name`)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON `messenger_messages` (`available_at`)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON `messenger_messages` (`delivered_at`)');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `book` DROP FOREIGN KEY `FK_CBE5A3316995AC4C`');
        $this->addSql('DROP TABLE IF EXISTS `book`');
        $this->addSql('DROP TABLE IF EXISTS `editor`');
        $this->addSql('DROP TABLE IF EXISTS `messenger_messages`');
    }
}