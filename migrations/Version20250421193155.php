<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421193155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update service table structure';
    }

    public function up(Schema $schema): void
    {
        // Drop old columns if they exist
        $this->addSql('ALTER TABLE service DROP COLUMN IF EXISTS wifi');
        $this->addSql('ALTER TABLE service DROP COLUMN IF EXISTS climatisation');
        $this->addSql('ALTER TABLE service DROP COLUMN IF EXISTS menage_quotidien');
        $this->addSql('ALTER TABLE service DROP COLUMN IF EXISTS conciergerie');
        $this->addSql('ALTER TABLE service DROP COLUMN IF EXISTS linge_lit');
        $this->addSql('ALTER TABLE service DROP COLUMN IF EXISTS salle_bain_privee');
    }

    public function down(Schema $schema): void
    {
        // Add back the columns
        $this->addSql('ALTER TABLE service ADD COLUMN wifi TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE service ADD COLUMN climatisation TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE service ADD COLUMN menage_quotidien TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE service ADD COLUMN conciergerie TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE service ADD COLUMN linge_lit TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE service ADD COLUMN salle_bain_privee TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
