<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190217181742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE routine_action_id_seq CASCADE');
        $this->addSql('DROP TABLE routine_action');
        $this->addSql('CREATE TABLE habit (id SERIAL NOT NULL, routine_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, duration BIGINT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_44FE2172F27A94C7 ON habit (routine_id)');
        $this->addSql('ALTER TABLE habit ADD CONSTRAINT FK_44FE2172F27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE routine_action_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE routine_action (id SERIAL NOT NULL, routine_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, duration BIGINT NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3e0b9edf27a94c7 ON routine_action (routine_id)');
        $this->addSql('ALTER TABLE routine_action ADD CONSTRAINT fk_3e0b9edf27a94c7 FOREIGN KEY (routine_id) REFERENCES routine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE habit');
        $this->addSql('ALTER TABLE character ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE action ALTER id DROP DEFAULT');
    }
}
