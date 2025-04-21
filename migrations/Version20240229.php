<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove old boolean columns from service table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE service DROP COLUMN wifi, DROP COLUMN climatisation, DROP COLUMN menage_quotidien, DROP COLUMN conciergerie, DROP COLUMN linge_lit, DROP COLUMN salle_bain_privee');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE service ADD COLUMN wifi TINYINT(1) DEFAULT 0 NOT NULL, ADD COLUMN climatisation TINYINT(1) DEFAULT 0 NOT NULL, ADD COLUMN menage_quotidien TINYINT(1) DEFAULT 0 NOT NULL, ADD COLUMN conciergerie TINYINT(1) DEFAULT 0 NOT NULL, ADD COLUMN linge_lit TINYINT(1) DEFAULT 0 NOT NULL, ADD COLUMN salle_bain_privee TINYINT(1) DEFAULT 0 NOT NULL');
    }
} 