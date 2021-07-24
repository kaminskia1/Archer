<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624194943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commerce_transaction');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commerce_transaction (id INT AUTO_INCREMENT NOT NULL, commerce_gateway_instance_id INT DEFAULT NULL, commerce_invoice_id INT DEFAULT NULL, commerce_purchase_id INT DEFAULT NULL, user_id INT DEFAULT NULL, creation_date_time DATETIME NOT NULL, amount DOUBLE PRECISION NOT NULL, staff_message VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, gateway_data LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', INDEX IDX_8BAD505C495A859F (commerce_gateway_instance_id), UNIQUE INDEX UNIQ_8BAD505CA031361 (commerce_invoice_id), INDEX IDX_8BAD505CA76ED395 (user_id), UNIQUE INDEX UNIQ_8BAD505CAC15EB34 (commerce_purchase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commerce_transaction ADD CONSTRAINT FK_8BAD505C495A859F FOREIGN KEY (commerce_gateway_instance_id) REFERENCES commerce_gateway_instance (id)');
        $this->addSql('ALTER TABLE commerce_transaction ADD CONSTRAINT FK_8BAD505CA031361 FOREIGN KEY (commerce_invoice_id) REFERENCES commerce_invoice (id)');
        $this->addSql('ALTER TABLE commerce_transaction ADD CONSTRAINT FK_8BAD505CA76ED395 FOREIGN KEY (user_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE commerce_transaction ADD CONSTRAINT FK_8BAD505CAC15EB34 FOREIGN KEY (commerce_purchase_id) REFERENCES commerce_purchase (id)');
    }
}
