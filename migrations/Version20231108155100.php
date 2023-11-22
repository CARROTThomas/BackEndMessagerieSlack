<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108155100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE relation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE relation (id INT NOT NULL, person_a_id INT NOT NULL, person_b_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62894749B138D773 ON relation (person_a_id)');
        $this->addSql('CREATE INDEX IDX_62894749A38D789D ON relation (person_b_id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749B138D773 FOREIGN KEY (person_a_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749A38D789D FOREIGN KEY (person_b_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE relation_id_seq CASCADE');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749B138D773');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749A38D789D');
        $this->addSql('DROP TABLE relation');
    }
}
