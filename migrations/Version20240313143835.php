<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration adaptée pour MySQL
 */
final class Version20240313143835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Création de la table `user` compatible MySQL (remplace la séquence Postgres)
        $this->addSql('CREATE TABLE `user` (
            id INT NOT NULL AUTO_INCREMENT,
            username VARCHAR(180) DEFAULT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON `user` (`email`)');
    }

    public function down(Schema $schema): void
    {
        // Suppression adaptée pour MySQL
        $this->addSql('DROP TABLE IF EXISTS `user`');
    }
}
