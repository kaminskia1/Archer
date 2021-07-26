<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725235208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commerce_license_key (id INT NOT NULL, package_id INT NOT NULL, invoice_id INT DEFAULT NULL, used_by_id INT DEFAULT NULL, code BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', duration VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_F996013F77153098 (code), INDEX IDX_F996013FF44CABFF (package_id), INDEX IDX_F996013F2989F1FD (invoice_id), INDEX IDX_F996013F4C2B72A8 (used_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commerce_license_key ADD CONSTRAINT FK_F996013FF44CABFF FOREIGN KEY (package_id) REFERENCES commerce_package (id)');
        $this->addSql('ALTER TABLE commerce_license_key ADD CONSTRAINT FK_F996013F2989F1FD FOREIGN KEY (invoice_id) REFERENCES commerce_invoice (id)');
        $this->addSql('ALTER TABLE commerce_license_key ADD CONSTRAINT FK_F996013F4C2B72A8 FOREIGN KEY (used_by_id) REFERENCES core_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commerce_license_key');
    }
}
