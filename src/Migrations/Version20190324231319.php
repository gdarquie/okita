<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190324231319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE action (id SERIAL NOT NULL, character_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, start_at BIGINT NOT NULL, end_at BIGINT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_47CC8C921136BE75 ON action (character_id)');
        $this->addSql('CREATE TABLE habit (id SERIAL NOT NULL, routine_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, start INT NOT NULL, "end" INT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_44FE2172F27A94C7 ON habit (routine_id)');
        $this->addSql('CREATE TABLE routine (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4BF6D8D65E237E06 ON routine (name)');
        $this->addSql('CREATE TABLE character (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, sex VARCHAR(1) NOT NULL, gender VARCHAR(2) DEFAULT NULL, birth_date BIGINT NOT NULL, death_date BIGINT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE character_routine (character_id INT NOT NULL, routine_id INT NOT NULL, PRIMARY KEY(character_id, routine_id))');
        $this->addSql('CREATE INDEX IDX_225358EB1136BE75 ON character_routine (character_id)');
        $this->addSql('CREATE INDEX IDX_225358EBF27A94C7 ON character_routine (routine_id)');
        $this->addSql('CREATE TABLE setting (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, key VARCHAR(255) NOT NULL, value VARCHAR(510) NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C921136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE habit ADD CONSTRAINT FK_44FE2172F27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_routine ADD CONSTRAINT FK_225358EB1136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_routine ADD CONSTRAINT FK_225358EBF27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE habit DROP CONSTRAINT FK_44FE2172F27A94C7');
        $this->addSql('ALTER TABLE character_routine DROP CONSTRAINT FK_225358EBF27A94C7');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C921136BE75');
        $this->addSql('ALTER TABLE character_routine DROP CONSTRAINT FK_225358EB1136BE75');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE habit');
        $this->addSql('DROP TABLE routine');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE character_routine');
        $this->addSql('DROP TABLE setting');
    }
}
