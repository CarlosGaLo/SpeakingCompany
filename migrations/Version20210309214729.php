<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210309214729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mailing_element ADD surname VARCHAR(255) DEFAULT NULL, ADD surname2 VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD last_course_date DATE DEFAULT NULL, ADD origin VARCHAR(255) DEFAULT NULL, ADD mail_nb INT DEFAULT NULL, ADD last_mail DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mailing_element DROP surname, DROP surname2, DROP phone, DROP last_course_date, DROP origin, DROP mail_nb, DROP last_mail');
    }
}
