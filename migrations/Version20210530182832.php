<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210530182832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logger_site_auth_login ADD uuid VARCHAR(255) NOT NULL, ADD ip VARCHAR(46) DEFAULT NULL, ADD base_path VARCHAR(255) NOT NULL, ADD user_agent VARCHAR(255) DEFAULT NULL, ADD language VARCHAR(255) DEFAULT NULL, ADD is_user_real TINYINT(1) NOT NULL, ADD is_auth_successful TINYINT(1) NOT NULL, ADD execution DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logger_site_auth_login DROP uuid, DROP ip, DROP base_path, DROP user_agent, DROP language, DROP is_user_real, DROP is_auth_successful, DROP execution');
    }
}
