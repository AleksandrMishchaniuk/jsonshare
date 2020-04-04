<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404183041 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hash ADD json_id INT NOT NULL');
        $this->addSql('ALTER TABLE hash ADD CONSTRAINT FK_D1B862B86687CF34 FOREIGN KEY (json_id) REFERENCES json (id)');
        $this->addSql('CREATE INDEX IDX_D1B862B86687CF34 ON hash (json_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hash DROP FOREIGN KEY FK_D1B862B86687CF34');
        $this->addSql('DROP INDEX IDX_D1B862B86687CF34 ON hash');
        $this->addSql('ALTER TABLE hash DROP json_id');
    }
}
