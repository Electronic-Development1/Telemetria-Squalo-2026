<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316160311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evento (id INT AUTO_INCREMENT NOT NULL, tipo INT NOT NULL, fecha_realizacion DATE NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE evento_piloto (evento_id INT NOT NULL, piloto_id INT NOT NULL, INDEX IDX_734EA09287A5F842 (evento_id), INDEX IDX_734EA0929AAD4A8D (piloto_id), PRIMARY KEY (evento_id, piloto_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE evento_piloto ADD CONSTRAINT FK_734EA09287A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evento_piloto ADD CONSTRAINT FK_734EA0929AAD4A8D FOREIGN KEY (piloto_id) REFERENCES piloto (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento_piloto DROP FOREIGN KEY FK_734EA09287A5F842');
        $this->addSql('ALTER TABLE evento_piloto DROP FOREIGN KEY FK_734EA0929AAD4A8D');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE evento_piloto');
    }
}
