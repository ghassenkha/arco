<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210711131716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE besoin (id INT AUTO_INCREMENT NOT NULL, no VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, s1 INT DEFAULT NULL, s2 INT DEFAULT NULL, s3 INT DEFAULT NULL, s4 INT DEFAULT NULL, s5 INT DEFAULT NULL, s6 INT DEFAULT NULL, s7 INT DEFAULT NULL, s8 INT DEFAULT NULL, s9 INT DEFAULT NULL, s10 INT DEFAULT NULL, s11 INT DEFAULT NULL, s12 INT DEFAULT NULL, s13 INT DEFAULT NULL, s14 INT DEFAULT NULL, s15 INT DEFAULT NULL, s16 INT DEFAULT NULL, somme INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE besoin');
    }
}
