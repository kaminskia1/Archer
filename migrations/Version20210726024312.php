<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210726024312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commerce_license_key ADD purchased_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commerce_license_key ADD CONSTRAINT FK_F996013F51D43F65 FOREIGN KEY (purchased_by_id) REFERENCES core_user (id)');
        $this->addSql('CREATE INDEX IDX_F996013F51D43F65 ON commerce_license_key (purchased_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commerce_license_key DROP FOREIGN KEY FK_F996013F51D43F65');
        $this->addSql('DROP INDEX IDX_F996013F51D43F65 ON commerce_license_key');
        $this->addSql('ALTER TABLE commerce_license_key DROP purchased_by_id');
    }
}
