<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220075558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE municipality (id INT AUTO_INCREMENT NOT NULL, town_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C6F5662875E23604 (town_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE municipality ADD CONSTRAINT FK_C6F5662875E23604 FOREIGN KEY (town_id) REFERENCES town (id)');
        $this->addSql('ALTER TABLE declaration ADD municipality_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE declaration ADD CONSTRAINT FK_7AA3DAC2AE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id)');
        $this->addSql('CREATE INDEX IDX_7AA3DAC2AE6F181C ON declaration (municipality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration DROP FOREIGN KEY FK_7AA3DAC2AE6F181C');
        $this->addSql('ALTER TABLE municipality DROP FOREIGN KEY FK_C6F5662875E23604');
        $this->addSql('DROP TABLE municipality');
        $this->addSql('DROP TABLE town');
        $this->addSql('DROP INDEX IDX_7AA3DAC2AE6F181C ON declaration');
        $this->addSql('ALTER TABLE declaration DROP municipality_id');
    }
}
