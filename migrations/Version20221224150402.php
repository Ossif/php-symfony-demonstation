<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221224150402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE friend (id INT AUTO_INCREMENT NOT NULL, outcoming_id INT NOT NULL, incoming_id INT NOT NULL, INDEX IDX_55EEAC61E1888830 (outcoming_id), INDEX IDX_55EEAC613F7C4BE7 (incoming_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61E1888830 FOREIGN KEY (outcoming_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC613F7C4BE7 FOREIGN KEY (incoming_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61E1888830');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC613F7C4BE7');
        $this->addSql('DROP TABLE friend');
    }
}
