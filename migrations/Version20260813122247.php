<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260813122247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crée la table background, migre background_color existant et lie chaque presentation à un Background';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE background (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) NOT NULL, color1 VARCHAR(7) NOT NULL, color2 VARCHAR(7) DEFAULT NULL, gradient_angle DOUBLE PRECISION DEFAULT NULL, image_filename VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE presentation ADD background_id INT DEFAULT NULL');
    }

    public function postUp(Schema $schema): void
    {
        $presentations = $this->connection->fetchAllAssociative(
            'SELECT id, background_color FROM presentation'
        );

        foreach ($presentations as $presentation) {
            $this->connection->insert('background', [
                'type' => 'solid',
                'color1' => $presentation['background_color'] ?? '#1a1a1a',
            ]);

            $backgroundId = (int) $this->connection->lastInsertId();

            $this->connection->update(
                'presentation',
                ['background_id' => $backgroundId],
                ['id' => $presentation['id']]
            );
        }

        $this->connection->executeStatement(
            'ALTER TABLE presentation MODIFY background_id INT NOT NULL'
        );
        $this->connection->executeStatement(
            'ALTER TABLE presentation ADD CONSTRAINT FK_9B66E893C93D69EA FOREIGN KEY (background_id) REFERENCES background (id)'
        );
        $this->connection->executeStatement(
            'CREATE INDEX IDX_9B66E893C93D69EA ON presentation (background_id)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE presentation DROP FOREIGN KEY FK_9B66E893C93D69EA');
        $this->addSql('DROP INDEX IDX_9B66E893C93D69EA ON presentation');
        $this->addSql('ALTER TABLE presentation DROP background_id');
        $this->addSql('DROP TABLE background');
    }
}
