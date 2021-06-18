<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210617173320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_user_core_group (core_user_id INT NOT NULL, core_group_id INT NOT NULL, INDEX IDX_E1DA6191B57966A6 (core_user_id), INDEX IDX_E1DA61914196AFE4 (core_group_id), PRIMARY KEY(core_user_id, core_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_user_core_group ADD CONSTRAINT FK_E1DA6191B57966A6 FOREIGN KEY (core_user_id) REFERENCES core_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_user_core_group ADD CONSTRAINT FK_E1DA61914196AFE4 FOREIGN KEY (core_group_id) REFERENCES core_group (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_user_core_group');
    }
}
