<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227171427 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mailing_element (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_C242628591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_forum_post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, module_id INT NOT NULL, reply_to_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, post LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, modify_date DATETIME NOT NULL, INDEX IDX_C8EE7C7A76ED395 (user_id), INDEX IDX_C8EE7C7AFC2B591 (module_id), INDEX IDX_C8EE7C7FFDF7169 (reply_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_test (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, time_minutes SMALLINT NOT NULL, pass_percent SMALLINT NOT NULL, try_chances SMALLINT NOT NULL, INDEX IDX_62433B00AFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_text (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, position SMALLINT NOT NULL, INDEX IDX_81B7E2CBAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_video (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, video_key VARCHAR(255) NOT NULL, video_url VARCHAR(255) NOT NULL, position SMALLINT NOT NULL, INDEX IDX_75CBAA42AFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE module_forum_post ADD CONSTRAINT FK_C8EE7C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE module_forum_post ADD CONSTRAINT FK_C8EE7C7AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE module_forum_post ADD CONSTRAINT FK_C8EE7C7FFDF7169 FOREIGN KEY (reply_to_id) REFERENCES module_forum_post (id)');
        $this->addSql('ALTER TABLE module_test ADD CONSTRAINT FK_62433B00AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE module_text ADD CONSTRAINT FK_81B7E2CBAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE module_video ADD CONSTRAINT FK_75CBAA42AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_forum_post DROP FOREIGN KEY FK_C8EE7C7AFC2B591');
        $this->addSql('ALTER TABLE module_test DROP FOREIGN KEY FK_62433B00AFC2B591');
        $this->addSql('ALTER TABLE module_text DROP FOREIGN KEY FK_81B7E2CBAFC2B591');
        $this->addSql('ALTER TABLE module_video DROP FOREIGN KEY FK_75CBAA42AFC2B591');
        $this->addSql('ALTER TABLE module_forum_post DROP FOREIGN KEY FK_C8EE7C7FFDF7169');
        $this->addSql('DROP TABLE mailing_element');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_forum_post');
        $this->addSql('DROP TABLE module_test');
        $this->addSql('DROP TABLE module_text');
        $this->addSql('DROP TABLE module_video');
    }
}
