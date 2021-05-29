<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210527002857 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logger_command_user_subscription ADD user_id INT NOT NULL, ADD package_id INT NOT NULL, ADD subscription_id INT DEFAULT NULL, ADD flagged TINYINT(1) NOT NULL, ADD flag_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE logger_command_user_subscription ADD CONSTRAINT FK_46B9B9DFA76ED395 FOREIGN KEY (user_id) REFERENCES core_user (id)');
        $this->addSql('ALTER TABLE logger_command_user_subscription ADD CONSTRAINT FK_46B9B9DFF44CABFF FOREIGN KEY (package_id) REFERENCES commerce_package (id)');
        $this->addSql('ALTER TABLE logger_command_user_subscription ADD CONSTRAINT FK_46B9B9DF9A1887DC FOREIGN KEY (subscription_id) REFERENCES commerce_user_subscription (id)');
        $this->addSql('CREATE INDEX IDX_46B9B9DFA76ED395 ON logger_command_user_subscription (user_id)');
        $this->addSql('CREATE INDEX IDX_46B9B9DFF44CABFF ON logger_command_user_subscription (package_id)');
        $this->addSql('CREATE INDEX IDX_46B9B9DF9A1887DC ON logger_command_user_subscription (subscription_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logger_command_user_subscription DROP FOREIGN KEY FK_46B9B9DFA76ED395');
        $this->addSql('ALTER TABLE logger_command_user_subscription DROP FOREIGN KEY FK_46B9B9DFF44CABFF');
        $this->addSql('ALTER TABLE logger_command_user_subscription DROP FOREIGN KEY FK_46B9B9DF9A1887DC');
        $this->addSql('DROP INDEX IDX_46B9B9DFA76ED395 ON logger_command_user_subscription');
        $this->addSql('DROP INDEX IDX_46B9B9DFF44CABFF ON logger_command_user_subscription');
        $this->addSql('DROP INDEX IDX_46B9B9DF9A1887DC ON logger_command_user_subscription');
        $this->addSql('ALTER TABLE logger_command_user_subscription DROP user_id, DROP package_id, DROP subscription_id, DROP flagged, DROP flag_type');
    }
}
