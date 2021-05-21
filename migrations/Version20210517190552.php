<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210517190552 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commerce_invoice CHANGE payment_state payment_state SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE core_registration_code DROP FOREIGN KEY FK_B82B27444C2B72A8');
        $this->addSql('DROP INDEX uniq_b82b27444c2b72a8 ON core_registration_code');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF232E784C2B72A8 ON core_registration_code (used_by_id)');
        $this->addSql('ALTER TABLE core_registration_code ADD CONSTRAINT FK_B82B27444C2B72A8 FOREIGN KEY (used_by_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_user DROP FOREIGN KEY FK_8D93D64967ABABB1');
        $this->addSql('ALTER TABLE core_user ADD infraction_points INT NOT NULL');
        $this->addSql('DROP INDEX uniq_8d93d649d17f50a6 ON core_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF76157CD17F50A6 ON core_user (uuid)');
        $this->addSql('DROP INDEX uniq_8d93d64967ababb1 ON core_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF76157C67ABABB1 ON core_user (registration_code_id)');
        $this->addSql('ALTER TABLE core_user ADD CONSTRAINT FK_8D93D64967ABABB1 FOREIGN KEY (registration_code_id) REFERENCES core_registration_code (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commerce_invoice CHANGE payment_state payment_state VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE core_registration_code DROP FOREIGN KEY FK_BF232E784C2B72A8');
        $this->addSql('DROP INDEX uniq_bf232e784c2b72a8 ON core_registration_code');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B82B27444C2B72A8 ON core_registration_code (used_by_id)');
        $this->addSql('ALTER TABLE core_registration_code ADD CONSTRAINT FK_BF232E784C2B72A8 FOREIGN KEY (used_by_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE core_user DROP FOREIGN KEY FK_BF76157C67ABABB1');
        $this->addSql('ALTER TABLE core_user DROP infraction_points');
        $this->addSql('DROP INDEX uniq_bf76157c67ababb1 ON core_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64967ABABB1 ON core_user (registration_code_id)');
        $this->addSql('DROP INDEX uniq_bf76157cd17f50a6 ON core_user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D17F50A6 ON core_user (uuid)');
        $this->addSql('ALTER TABLE core_user ADD CONSTRAINT FK_BF76157C67ABABB1 FOREIGN KEY (registration_code_id) REFERENCES core_registration_code (id)');
    }
}
