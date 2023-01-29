<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230128202153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD fund_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D125A38F89 FOREIGN KEY (fund_id) REFERENCES fund (id)');
        $this->addSql('CREATE INDEX IDX_723705D125A38F89 ON transaction (fund_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D125A38F89');
        $this->addSql('DROP INDEX IDX_723705D125A38F89 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP fund_id');
    }
}
