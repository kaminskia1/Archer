<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210707233246 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE logger_site_request');
        $this->addSql('ALTER TABLE commerce_package ADD package_user_group_id INT DEFAULT NULL, ADD is_key_enabled TINYINT(1) NOT NULL, ADD key_duration_to_price LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP package_user_role');
        $this->addSql('ALTER TABLE commerce_package ADD CONSTRAINT FK_6DE5633FD01CC5C7 FOREIGN KEY (package_user_group_id) REFERENCES core_group (id)');
        $this->addSql('CREATE INDEX IDX_6DE5633FD01CC5C7 ON commerce_package (package_user_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE logger_site_request (id INT AUTO_INCREMENT NOT NULL, execution DATETIME NOT NULL, route VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(46) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, user_agent VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, host VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, method VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, locale VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, base_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, query VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_secure TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commerce_package DROP FOREIGN KEY FK_6DE5633FD01CC5C7');
        $this->addSql('DROP INDEX IDX_6DE5633FD01CC5C7 ON commerce_package');
        $this->addSql('ALTER TABLE commerce_package ADD package_user_role VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP package_user_group_id, DROP is_key_enabled, DROP key_duration_to_price');
    }
}
