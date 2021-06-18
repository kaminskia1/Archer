<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210617041336 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_group (id INT AUTO_INCREMENT NOT NULL, inherits_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, permissions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', color VARCHAR(7) NOT NULL, INDEX IDX_3B41652548546A3E (inherits_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_group_core_user (core_group_id INT NOT NULL, core_user_id INT NOT NULL, INDEX IDX_2398BC54196AFE4 (core_group_id), INDEX IDX_2398BC5B57966A6 (core_user_id), PRIMARY KEY(core_group_id, core_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_group ADD CONSTRAINT FK_3B41652548546A3E FOREIGN KEY (inherits_id) REFERENCES core_group (id)');
        $this->addSql('ALTER TABLE core_group_core_user ADD CONSTRAINT FK_2398BC54196AFE4 FOREIGN KEY (core_group_id) REFERENCES core_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_group_core_user ADD CONSTRAINT FK_2398BC5B57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE core_group DROP FOREIGN KEY FK_3B41652548546A3E');
        $this->addSql('ALTER TABLE core_group_core_user DROP FOREIGN KEY FK_2398BC54196AFE4');
        $this->addSql('DROP TABLE core_group');
        $this->addSql('DROP TABLE core_group_core_user');
    }
}
