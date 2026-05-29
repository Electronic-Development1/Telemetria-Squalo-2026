<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260522220938 extends AbstractMigration
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
        $this->addSql('CREATE TABLE piloto (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, movil VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE timer_cambio (id INT AUTO_INCREMENT NOT NULL, fecha_inicio DATETIME NOT NULL, fecha_final DATETIME NOT NULL, tiempo VARCHAR(20) DEFAULT NULL, piloto_sale_id INT NOT NULL, piloto_entra_id INT DEFAULT NULL, evento_id INT DEFAULT NULL, INDEX IDX_BF1A2F7F7BAA44A8 (piloto_sale_id), INDEX IDX_BF1A2F7FB4CE208E (piloto_entra_id), INDEX IDX_BF1A2F7F87A5F842 (evento_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE timer_danos (id INT AUTO_INCREMENT NOT NULL, numero_vuelta INT NOT NULL, fecha_inicio DATETIME NOT NULL, fecha_final DATETIME NOT NULL, tiempo VARCHAR(20) DEFAULT NULL, piloto_id INT NOT NULL, evento_id INT DEFAULT NULL, INDEX IDX_3D8EFC049AAD4A8D (piloto_id), INDEX IDX_3D8EFC0487A5F842 (evento_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE timer_meta (id INT AUTO_INCREMENT NOT NULL, numero_vuelta INT NOT NULL, fecha_inicio DATETIME NOT NULL, fecha_final DATETIME NOT NULL, tiempo VARCHAR(20) DEFAULT NULL, piloto_id INT NOT NULL, evento_id INT DEFAULT NULL, INDEX IDX_749793BA9AAD4A8D (piloto_id), INDEX IDX_749793BA87A5F842 (evento_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE timer_piloto (id INT AUTO_INCREMENT NOT NULL, numero_vueltas INT NOT NULL, cronometro VARCHAR(255) NOT NULL, fecha_inicio DATETIME NOT NULL, fecha_final DATETIME NOT NULL, piloto_id INT DEFAULT NULL, evento_id INT DEFAULT NULL, INDEX IDX_45208CA19AAD4A8D (piloto_id), INDEX IDX_45208CA187A5F842 (evento_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE timer_pits (id INT AUTO_INCREMENT NOT NULL, numero_vuelta INT NOT NULL, fecha_inicio DATETIME NOT NULL, fecha_final DATETIME NOT NULL, tiempo VARCHAR(20) DEFAULT NULL, piloto_id INT NOT NULL, evento_id INT DEFAULT NULL, INDEX IDX_2C4794D49AAD4A8D (piloto_id), INDEX IDX_2C4794D487A5F842 (evento_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE evento_piloto ADD CONSTRAINT FK_734EA09287A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evento_piloto ADD CONSTRAINT FK_734EA0929AAD4A8D FOREIGN KEY (piloto_id) REFERENCES piloto (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE timer_cambio ADD CONSTRAINT FK_BF1A2F7F7BAA44A8 FOREIGN KEY (piloto_sale_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_cambio ADD CONSTRAINT FK_BF1A2F7FB4CE208E FOREIGN KEY (piloto_entra_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_cambio ADD CONSTRAINT FK_BF1A2F7F87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE timer_danos ADD CONSTRAINT FK_3D8EFC049AAD4A8D FOREIGN KEY (piloto_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_danos ADD CONSTRAINT FK_3D8EFC0487A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE timer_meta ADD CONSTRAINT FK_749793BA9AAD4A8D FOREIGN KEY (piloto_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_meta ADD CONSTRAINT FK_749793BA87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE timer_piloto ADD CONSTRAINT FK_45208CA19AAD4A8D FOREIGN KEY (piloto_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_piloto ADD CONSTRAINT FK_45208CA187A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE timer_pits ADD CONSTRAINT FK_2C4794D49AAD4A8D FOREIGN KEY (piloto_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_pits ADD CONSTRAINT FK_2C4794D487A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento_piloto DROP FOREIGN KEY FK_734EA09287A5F842');
        $this->addSql('ALTER TABLE evento_piloto DROP FOREIGN KEY FK_734EA0929AAD4A8D');
        $this->addSql('ALTER TABLE timer_cambio DROP FOREIGN KEY FK_BF1A2F7F7BAA44A8');
        $this->addSql('ALTER TABLE timer_cambio DROP FOREIGN KEY FK_BF1A2F7FB4CE208E');
        $this->addSql('ALTER TABLE timer_cambio DROP FOREIGN KEY FK_BF1A2F7F87A5F842');
        $this->addSql('ALTER TABLE timer_danos DROP FOREIGN KEY FK_3D8EFC049AAD4A8D');
        $this->addSql('ALTER TABLE timer_danos DROP FOREIGN KEY FK_3D8EFC0487A5F842');
        $this->addSql('ALTER TABLE timer_meta DROP FOREIGN KEY FK_749793BA9AAD4A8D');
        $this->addSql('ALTER TABLE timer_meta DROP FOREIGN KEY FK_749793BA87A5F842');
        $this->addSql('ALTER TABLE timer_piloto DROP FOREIGN KEY FK_45208CA19AAD4A8D');
        $this->addSql('ALTER TABLE timer_piloto DROP FOREIGN KEY FK_45208CA187A5F842');
        $this->addSql('ALTER TABLE timer_pits DROP FOREIGN KEY FK_2C4794D49AAD4A8D');
        $this->addSql('ALTER TABLE timer_pits DROP FOREIGN KEY FK_2C4794D487A5F842');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE evento_piloto');
        $this->addSql('DROP TABLE piloto');
        $this->addSql('DROP TABLE timer_cambio');
        $this->addSql('DROP TABLE timer_danos');
        $this->addSql('DROP TABLE timer_meta');
        $this->addSql('DROP TABLE timer_piloto');
        $this->addSql('DROP TABLE timer_pits');
    }
}
