<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412105902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mailing_element ADD rol_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mailing_element ADD CONSTRAINT FK_5090BB568354C514 FOREIGN KEY (rol_type_id) REFERENCES mailing_element_rol (id)');
        $this->addSql('CREATE INDEX IDX_5090BB568354C514 ON mailing_element (rol_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mailing_element DROP FOREIGN KEY FK_5090BB568354C514');
        $this->addSql('DROP INDEX IDX_5090BB568354C514 ON mailing_element');
        $this->addSql('ALTER TABLE mailing_element DROP rol_type_id');
    }
}
