<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190214215509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE action_id_seq1 CASCADE');
        $this->addSql('DROP SEQUENCE character_id_seq1 CASCADE');
        $this->addSql('CREATE TABLE routine (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE routine_action (id SERIAL NOT NULL, routine_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, duration BIGINT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3E0B9EDF27A94C7 ON routine_action (routine_id)');
        $this->addSql('ALTER TABLE routine_action ADD CONSTRAINT FK_3E0B9EDF27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action ALTER start_at TYPE BIGINT');
        $this->addSql('ALTER TABLE action ALTER start_at DROP DEFAULT');
        $this->addSql('ALTER TABLE action ALTER end_at TYPE BIGINT');
        $this->addSql('ALTER TABLE action ALTER end_at DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE routine_action DROP CONSTRAINT FK_3E0B9EDF27A94C7');
        $this->addSql('CREATE SEQUENCE action_id_seq1 INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE character_id_seq1 INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE routine');
        $this->addSql('DROP TABLE routine_action');
        $this->addSql('ALTER TABLE action ALTER start_at TYPE INT');
        $this->addSql('ALTER TABLE action ALTER start_at DROP DEFAULT');
        $this->addSql('ALTER TABLE action ALTER end_at TYPE INT');
        $this->addSql('ALTER TABLE action ALTER end_at DROP DEFAULT');
    }
}
