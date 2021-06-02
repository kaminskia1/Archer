<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210530185940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logger_site_request ADD execution DATETIME NOT NULL, ADD uuid BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD route VARCHAR(255) DEFAULT NULL, ADD ip VARCHAR(46) DEFAULT NULL, ADD user_agent VARCHAR(255) DEFAULT NULL, ADD response_code VARCHAR(255) DEFAULT NULL, ADD host VARCHAR(255) DEFAULT NULL, ADD method VARCHAR(255) NOT NULL, ADD locale VARCHAR(255) DEFAULT NULL, ADD base_path VARCHAR(255) DEFAULT NULL, ADD query VARCHAR(255) DEFAULT NULL, ADD is_secure TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logger_site_request DROP execution, DROP uuid, DROP route, DROP ip, DROP user_agent, DROP response_code, DROP host, DROP method, DROP locale, DROP base_path, DROP query, DROP is_secure');
    }
}
