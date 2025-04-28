<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428081323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495552FF8701 FOREIGN KEY (id_clinique) REFERENCES clinique (id_clinique)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955E69E9D09 FOREIGN KEY (id_transport) REFERENCES transport (id_transport)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C849555040106B FOREIGN KEY (id_hebergement) REFERENCES hebergement (id_hebergement)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C8495552FF8701 ON reservation (id_clinique)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955E69E9D09 ON reservation (id_transport)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C849555040106B ON reservation (id_hebergement)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role CHANGE id_role id_role INT AUTO_INCREMENT NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP INDEX id_hebergement, ADD UNIQUE INDEX UNIQ_E19D9AD25040106B (id_hebergement)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service CHANGE wifi wifi TINYINT(1) DEFAULT 0 NOT NULL, CHANGE climatisation climatisation TINYINT(1) DEFAULT 0 NOT NULL, CHANGE menage_quotidien menage_quotidien TINYINT(1) DEFAULT 0 NOT NULL, CHANGE conciergerie conciergerie TINYINT(1) DEFAULT 0 NOT NULL, CHANGE linge_lit linge_lit TINYINT(1) DEFAULT 0 NOT NULL, CHANGE salle_bain_privee salle_bain_privee TINYINT(1) DEFAULT 0 NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service ADD CONSTRAINT FK_E19D9AD25040106B FOREIGN KEY (id_hebergement) REFERENCES hebergement (id_hebergement)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE statut CHANGE id id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur DROP FOREIGN KEY utilisateur_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur CHANGE telephone telephone VARCHAR(255) NOT NULL, CHANGE date_naissance date_naissance DATETIME DEFAULT NULL, CHANGE nationalite nationalite VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3DC499668 FOREIGN KEY (id_role) REFERENCES role (id_role)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495552FF8701
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955E69E9D09
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C849555040106B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C8495552FF8701 ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C84955E69E9D09 ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C849555040106B ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role CHANGE id_role id_role INT NOT NULL, CHANGE nom nom VARCHAR(50) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP INDEX UNIQ_E19D9AD25040106B, ADD INDEX id_hebergement (id_hebergement)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD25040106B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service CHANGE wifi wifi TINYINT(1) DEFAULT 0, CHANGE climatisation climatisation TINYINT(1) DEFAULT 0, CHANGE menage_quotidien menage_quotidien TINYINT(1) DEFAULT 0, CHANGE conciergerie conciergerie TINYINT(1) DEFAULT 0, CHANGE linge_lit linge_lit TINYINT(1) DEFAULT 0, CHANGE salle_bain_privee salle_bain_privee TINYINT(1) DEFAULT 0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE statut CHANGE id id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3DC499668
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur CHANGE telephone telephone VARCHAR(20) NOT NULL, CHANGE date_naissance date_naissance DATE NOT NULL, CHANGE nationalite nationalite VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD CONSTRAINT utilisateur_ibfk_1 FOREIGN KEY (id_role) REFERENCES role (id_role) ON DELETE CASCADE
        SQL);
    }
}
