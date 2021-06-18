<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210617051441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE core_group_core_group (core_group_source INT NOT NULL, core_group_target INT NOT NULL, INDEX IDX_8941219321BC1E7B (core_group_source), INDEX IDX_8941219338594EF4 (core_group_target), PRIMARY KEY(core_group_source, core_group_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE core_group_core_group ADD CONSTRAINT FK_8941219321BC1E7B FOREIGN KEY (core_group_source) REFERENCES core_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_group_core_group ADD CONSTRAINT FK_8941219338594EF4 FOREIGN KEY (core_group_target) REFERENCES core_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE core_group DROP FOREIGN KEY FK_3B41652548546A3E');
        $this->addSql('DROP INDEX IDX_3B41652548546A3E ON core_group');
        $this->addSql('ALTER TABLE core_group DROP inherits_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE core_group_core_group');
        $this->addSql('ALTER TABLE core_group ADD inherits_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE core_group ADD CONSTRAINT FK_3B41652548546A3E FOREIGN KEY (inherits_id) REFERENCES core_group (id)');
        $this->addSql('CREATE INDEX IDX_3B41652548546A3E ON core_group (inherits_id)');
    }
}
