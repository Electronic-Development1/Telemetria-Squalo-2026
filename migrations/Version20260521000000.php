<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Crea las tablas separadas para cada cronómetro:
 *   - timer_meta       → META → META
 *   - timer_pits       → PITS → PITS
 *   - timer_cambio     → CAMBIO DE PILOTO (guarda piloto_sale y piloto_entra)
 *   - timer_danos      → DAÑOS
 */
final class Version20260521000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Tablas separadas por tipo de cronómetro: timer_meta, timer_pits, timer_cambio, timer_danos';
    }

    public function up(Schema $schema): void
    {
        // ── TIMER META ───────────────────────────────────────────────────────
        $this->addSql('
            CREATE TABLE timer_meta (
                id             INT AUTO_INCREMENT NOT NULL,
                piloto_id      INT NOT NULL,
                evento_id      INT DEFAULT NULL,
                numero_vuelta  INT NOT NULL,
                fecha_inicio   DATETIME NOT NULL,
                fecha_final    DATETIME NOT NULL,
                tiempo         VARCHAR(20) DEFAULT NULL,
                INDEX IDX_TIMER_META_PILOTO  (piloto_id),
                INDEX IDX_TIMER_META_EVENTO  (evento_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        ');
        $this->addSql('ALTER TABLE timer_meta ADD CONSTRAINT FK_TIMER_META_PILOTO FOREIGN KEY (piloto_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_meta ADD CONSTRAINT FK_TIMER_META_EVENTO FOREIGN KEY (evento_id) REFERENCES evento (id)');

        // ── TIMER PITS ───────────────────────────────────────────────────────
        $this->addSql('
            CREATE TABLE timer_pits (
                id             INT AUTO_INCREMENT NOT NULL,
                piloto_id      INT NOT NULL,
                evento_id      INT DEFAULT NULL,
                numero_vuelta  INT NOT NULL,
                fecha_inicio   DATETIME NOT NULL,
                fecha_final    DATETIME NOT NULL,
                tiempo         VARCHAR(20) DEFAULT NULL,
                INDEX IDX_TIMER_PITS_PILOTO  (piloto_id),
                INDEX IDX_TIMER_PITS_EVENTO  (evento_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        ');
        $this->addSql('ALTER TABLE timer_pits ADD CONSTRAINT FK_TIMER_PITS_PILOTO FOREIGN KEY (piloto_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_pits ADD CONSTRAINT FK_TIMER_PITS_EVENTO FOREIGN KEY (evento_id) REFERENCES evento (id)');

        // ── TIMER CAMBIO ─────────────────────────────────────────────────────
        // piloto_sale_id = piloto que sale (obligatorio)
        // piloto_entra_id = piloto que entra (puede ser NULL si no se seleccionó)
        $this->addSql('
            CREATE TABLE timer_cambio (
                id                INT AUTO_INCREMENT NOT NULL,
                piloto_sale_id    INT NOT NULL,
                piloto_entra_id   INT DEFAULT NULL,
                evento_id         INT DEFAULT NULL,
                fecha_inicio      DATETIME NOT NULL,
                fecha_final       DATETIME NOT NULL,
                tiempo            VARCHAR(20) DEFAULT NULL,
                INDEX IDX_TIMER_CAMBIO_SALE   (piloto_sale_id),
                INDEX IDX_TIMER_CAMBIO_ENTRA  (piloto_entra_id),
                INDEX IDX_TIMER_CAMBIO_EVENTO (evento_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        ');
        $this->addSql('ALTER TABLE timer_cambio ADD CONSTRAINT FK_TIMER_CAMBIO_SALE   FOREIGN KEY (piloto_sale_id)   REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_cambio ADD CONSTRAINT FK_TIMER_CAMBIO_ENTRA  FOREIGN KEY (piloto_entra_id)  REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_cambio ADD CONSTRAINT FK_TIMER_CAMBIO_EVENTO FOREIGN KEY (evento_id)        REFERENCES evento (id)');

        // ── TIMER DAÑOS ──────────────────────────────────────────────────────
        $this->addSql('
            CREATE TABLE timer_danos (
                id             INT AUTO_INCREMENT NOT NULL,
                piloto_id      INT NOT NULL,
                evento_id      INT DEFAULT NULL,
                numero_vuelta  INT NOT NULL,
                fecha_inicio   DATETIME NOT NULL,
                fecha_final    DATETIME NOT NULL,
                tiempo         VARCHAR(20) DEFAULT NULL,
                INDEX IDX_TIMER_DANOS_PILOTO  (piloto_id),
                INDEX IDX_TIMER_DANOS_EVENTO  (evento_id),
                PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        ');
        $this->addSql('ALTER TABLE timer_danos ADD CONSTRAINT FK_TIMER_DANOS_PILOTO FOREIGN KEY (piloto_id) REFERENCES piloto (id)');
        $this->addSql('ALTER TABLE timer_danos ADD CONSTRAINT FK_TIMER_DANOS_EVENTO FOREIGN KEY (evento_id) REFERENCES evento (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE timer_meta   DROP FOREIGN KEY FK_TIMER_META_PILOTO');
        $this->addSql('ALTER TABLE timer_meta   DROP FOREIGN KEY FK_TIMER_META_EVENTO');
        $this->addSql('ALTER TABLE timer_pits   DROP FOREIGN KEY FK_TIMER_PITS_PILOTO');
        $this->addSql('ALTER TABLE timer_pits   DROP FOREIGN KEY FK_TIMER_PITS_EVENTO');
        $this->addSql('ALTER TABLE timer_cambio DROP FOREIGN KEY FK_TIMER_CAMBIO_SALE');
        $this->addSql('ALTER TABLE timer_cambio DROP FOREIGN KEY FK_TIMER_CAMBIO_ENTRA');
        $this->addSql('ALTER TABLE timer_cambio DROP FOREIGN KEY FK_TIMER_CAMBIO_EVENTO');
        $this->addSql('ALTER TABLE timer_danos  DROP FOREIGN KEY FK_TIMER_DANOS_PILOTO');
        $this->addSql('ALTER TABLE timer_danos  DROP FOREIGN KEY FK_TIMER_DANOS_EVENTO');
        $this->addSql('DROP TABLE timer_meta');
        $this->addSql('DROP TABLE timer_pits');
        $this->addSql('DROP TABLE timer_cambio');
        $this->addSql('DROP TABLE timer_danos');
    }
}
