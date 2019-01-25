<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190125073501 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE OR REPLACE FUNCTION name()
          RETURNS VARCHAR AS
          $$
          BEGIN
            RETURN \'nom\';
          END
          $$ LANGUAGE plpgsql');
        $this->addSql('CREATE OR REPLACE FUNCTION generate_characters(count integer) RETURNS VOID AS $$
        DECLARE
        counter integer := 0;
          BEGIN
            WHILE counter < count
              LOOP
                INSERT INTO character (id, name, description) VALUES (counter, name(), \'description\') ;
                counter := counter + 1;
              END LOOP;
          END;
      $$ LANGUAGE plpgsql');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP FUNCTION name()');
        $this->addSql('DROP FUNCTION generate_characters(integer)');
        $this->addSql('CREATE SCHEMA public');
    }
}
