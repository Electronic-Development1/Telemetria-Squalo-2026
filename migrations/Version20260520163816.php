<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260520163816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE timer_piloto (id INT AUTO_INCREMENT NOT NULL, numero_vueltas INT NOT NULL, cronometro VARCHAR(255) NOT NULL, fecha_inicio DATETIME NOT NULL, fecha_final DATETIME NOT NULL, piloto_id INT DEFAULT NULL, evento_id INT DEFAULT NULL, INDEX IDX_45208CA19AAD4A8D (piloto_id), INDEX IDX_45208CA187A5F842 (evento_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE timer_piloto ADD CONSTRAINT FK_45208CA19AAD4A8D FOREIGN KEY (piloto_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_piloto ADD CONSTRAINT FK_45208CA187A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timer_piloto DROP FOREIGN KEY FK_45208CA19AAD4A8D');
        $this->addSql('ALTER TABLE timer_piloto DROP FOREIGN KEY FK_45208CA187A5F842');
        $this->addSql('DROP TABLE timer_piloto');
    }
}
