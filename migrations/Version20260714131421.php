<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260714131421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE presentation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image_filename VARCHAR(255) DEFAULT NULL, background_color VARCHAR(7) NOT NULL, format VARCHAR(20) NOT NULL, pos_x DOUBLE PRECISION NOT NULL, pos_y DOUBLE PRECISION NOT NULL, scale DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, owner_id INT NOT NULL, INDEX IDX_9B66E8937E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE presentation ADD CONSTRAINT FK_9B66E8937E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presentation DROP FOREIGN KEY FK_9B66E8937E3C61F9');
        $this->addSql('DROP TABLE presentation');
    }
}
