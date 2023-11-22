<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110112435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE private_conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE private_conversation (id INT NOT NULL, individual_a_id INT NOT NULL, individual_b_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCF38EEBF31CC9EB ON private_conversation (individual_a_id)');
        $this->addSql('CREATE INDEX IDX_DCF38EEBE1A96605 ON private_conversation (individual_b_id)');
        $this->addSql('ALTER TABLE private_conversation ADD CONSTRAINT FK_DCF38EEBF31CC9EB FOREIGN KEY (individual_a_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_conversation ADD CONSTRAINT FK_DCF38EEBE1A96605 FOREIGN KEY (individual_b_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_profile DROP CONSTRAINT fk_52e9749337a01814');
        $this->addSql('ALTER TABLE profile_profile DROP CONSTRAINT fk_52e974932e45489b');
        $this->addSql('DROP TABLE profile_profile');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE private_conversation_id_seq CASCADE');
        $this->addSql('CREATE TABLE profile_profile (profile_source INT NOT NULL, profile_target INT NOT NULL, PRIMARY KEY(profile_source, profile_target))');
        $this->addSql('CREATE INDEX idx_52e974932e45489b ON profile_profile (profile_target)');
        $this->addSql('CREATE INDEX idx_52e9749337a01814 ON profile_profile (profile_source)');
        $this->addSql('ALTER TABLE profile_profile ADD CONSTRAINT fk_52e9749337a01814 FOREIGN KEY (profile_source) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_profile ADD CONSTRAINT fk_52e974932e45489b FOREIGN KEY (profile_target) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_conversation DROP CONSTRAINT FK_DCF38EEBF31CC9EB');
        $this->addSql('ALTER TABLE private_conversation DROP CONSTRAINT FK_DCF38EEBE1A96605');
        $this->addSql('DROP TABLE private_conversation');
    }
}
