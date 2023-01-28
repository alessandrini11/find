<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128161448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE archive (id INT AUTO_INCREMENT NOT NULL, declaration_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, actor_id INT DEFAULT NULL, owner_validation TINYINT(1) NOT NULL, actor_validation TINYINT(1) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_D5FC5D9CC06258A3 (declaration_id), INDEX IDX_D5FC5D9C7E3C61F9 (owner_id), INDEX IDX_D5FC5D9C10DAF24A (actor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE declaration (id INT AUTO_INCREMENT NOT NULL, document_id INT DEFAULT NULL, user_id INT DEFAULT NULL, description LONGTEXT NOT NULL, reward INT DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_7AA3DAC2C33F7837 (document_id), INDEX IDX_7AA3DAC2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, owner VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, id_number VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_D8698A76A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fund (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_DC923E10A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payement (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, montant INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B20A7885A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, montant INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitor (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visitor_declaration (visitor_id INT NOT NULL, declaration_id INT NOT NULL, INDEX IDX_20CC46D570BEE6D (visitor_id), INDEX IDX_20CC46D5C06258A3 (declaration_id), PRIMARY KEY(visitor_id, declaration_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE archive ADD CONSTRAINT FK_D5FC5D9CC06258A3 FOREIGN KEY (declaration_id) REFERENCES declaration (id)');
        $this->addSql('ALTER TABLE archive ADD CONSTRAINT FK_D5FC5D9C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE archive ADD CONSTRAINT FK_D5FC5D9C10DAF24A FOREIGN KEY (actor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE declaration ADD CONSTRAINT FK_7AA3DAC2C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE declaration ADD CONSTRAINT FK_7AA3DAC2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fund ADD CONSTRAINT FK_DC923E10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE payement ADD CONSTRAINT FK_B20A7885A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE visitor_declaration ADD CONSTRAINT FK_20CC46D570BEE6D FOREIGN KEY (visitor_id) REFERENCES visitor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visitor_declaration ADD CONSTRAINT FK_20CC46D5C06258A3 FOREIGN KEY (declaration_id) REFERENCES declaration (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archive DROP FOREIGN KEY FK_D5FC5D9CC06258A3');
        $this->addSql('ALTER TABLE archive DROP FOREIGN KEY FK_D5FC5D9C7E3C61F9');
        $this->addSql('ALTER TABLE archive DROP FOREIGN KEY FK_D5FC5D9C10DAF24A');
        $this->addSql('ALTER TABLE declaration DROP FOREIGN KEY FK_7AA3DAC2C33F7837');
        $this->addSql('ALTER TABLE declaration DROP FOREIGN KEY FK_7AA3DAC2A76ED395');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76A76ED395');
        $this->addSql('ALTER TABLE fund DROP FOREIGN KEY FK_DC923E10A76ED395');
        $this->addSql('ALTER TABLE payement DROP FOREIGN KEY FK_B20A7885A76ED395');
        $this->addSql('ALTER TABLE visitor_declaration DROP FOREIGN KEY FK_20CC46D570BEE6D');
        $this->addSql('ALTER TABLE visitor_declaration DROP FOREIGN KEY FK_20CC46D5C06258A3');
        $this->addSql('DROP TABLE archive');
        $this->addSql('DROP TABLE declaration');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE fund');
        $this->addSql('DROP TABLE payement');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE visitor');
        $this->addSql('DROP TABLE visitor_declaration');
    }
}
