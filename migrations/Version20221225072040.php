<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221225072040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE black_list (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, admin_id_id INT NOT NULL, reason VARCHAR(255) DEFAULT NULL, datetime_begin DATETIME NOT NULL, datetime_end DATETIME NOT NULL, INDEX IDX_972CB8519D86650F (user_id_id), INDEX IDX_972CB851DF6E65AD (admin_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE black_list ADD CONSTRAINT FK_972CB8519D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE black_list ADD CONSTRAINT FK_972CB851DF6E65AD FOREIGN KEY (admin_id_id) REFERENCES `admin` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE black_list DROP FOREIGN KEY FK_972CB8519D86650F');
        $this->addSql('ALTER TABLE black_list DROP FOREIGN KEY FK_972CB851DF6E65AD');
        $this->addSql('DROP TABLE black_list');
    }
}
