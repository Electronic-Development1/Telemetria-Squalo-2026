<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260529172526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timer_piloto ADD piloto_entra_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE timer_piloto ADD CONSTRAINT FK_45208CA1B4CE208E FOREIGN KEY (piloto_entra_id) REFERENCES piloto (id)');
        $this->addSql('CREATE INDEX IDX_45208CA1B4CE208E ON timer_piloto (piloto_entra_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE timer_piloto DROP FOREIGN KEY FK_45208CA1B4CE208E');
        $this->addSql('DROP INDEX IDX_45208CA1B4CE208E ON timer_piloto');
        $this->addSql('ALTER TABLE timer_piloto DROP piloto_entra_id');
    }
}
