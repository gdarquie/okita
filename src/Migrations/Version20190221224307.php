<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190221224307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE character_routine (character_id INT NOT NULL, routine_id INT NOT NULL, PRIMARY KEY(character_id, routine_id))');
        $this->addSql('CREATE INDEX IDX_225358EB1136BE75 ON character_routine (character_id)');
        $this->addSql('CREATE INDEX IDX_225358EBF27A94C7 ON character_routine (routine_id)');
        $this->addSql('ALTER TABLE character_routine ADD CONSTRAINT FK_225358EB1136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_routine ADD CONSTRAINT FK_225358EBF27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE character_routine');
    }
}
