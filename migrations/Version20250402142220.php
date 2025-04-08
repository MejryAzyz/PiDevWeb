<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402142220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accompagnateur CHANGE id_accompagnateur id_accompagnateur INT NOT NULL, CHANGE photo_profil photo_profil VARCHAR(255) NOT NULL, CHANGE experience experience LONGTEXT NOT NULL, CHANGE motivation motivation LONGTEXT NOT NULL, CHANGE langues langues VARCHAR(255) NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE date_recrutement date_recrutement DATE NOT NULL');
        $this->addSql('ALTER TABLE offre_emploi CHANGE id id BIGINT NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE date_publication date_publication DATE NOT NULL');
        $this->addSql('DROP INDEX paiement_ibfk_1 ON paiement');
        $this->addSql('ALTER TABLE paiement CHANGE id_reservation id_reservation INT NOT NULL, CHANGE montant montant DOUBLE PRECISION NOT NULL, CHANGE date_paiement date_paiement DATETIME NOT NULL, CHANGE methode methode VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE planning_accompagnateur DROP FOREIGN KEY planning_accompagnateur_ibfk_1');
        $this->addSql('ALTER TABLE planning_accompagnateur CHANGE id_planning id_planning INT NOT NULL, CHANGE heure_debut heure_debut VARCHAR(255) NOT NULL, CHANGE heure_fin heure_fin VARCHAR(255) NOT NULL, CHANGE statut statut VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX id_accompagnateur ON planning_accompagnateur');
        $this->addSql('CREATE INDEX IDX_BDDF4B5D8492CD27 ON planning_accompagnateur (id_accompagnateur)');
        $this->addSql('ALTER TABLE planning_accompagnateur ADD CONSTRAINT planning_accompagnateur_ibfk_1 FOREIGN KEY (id_accompagnateur) REFERENCES accompagnateur (id_accompagnateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning_docteur DROP FOREIGN KEY planning_docteur_ibfk_1');
        $this->addSql('ALTER TABLE planning_docteur CHANGE id_planning id_planning INT NOT NULL, CHANGE heure_debut heure_debut VARCHAR(255) NOT NULL, CHANGE heure_fin heure_fin VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX id_docteur ON planning_docteur');
        $this->addSql('CREATE INDEX IDX_5ECAF9D25D3A0D49 ON planning_docteur (id_docteur)');
        $this->addSql('ALTER TABLE planning_docteur ADD CONSTRAINT planning_docteur_ibfk_1 FOREIGN KEY (id_docteur) REFERENCES docteur (id_docteur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation CHANGE id_reservation id_reservation INT NOT NULL, CHANGE id_clinique id_clinique INT NOT NULL, CHANGE id_transport id_transport INT NOT NULL, CHANGE date_depart date_depart DATE NOT NULL, CHANGE heure_depart heure_depart VARCHAR(255) NOT NULL, CHANGE id_hebergement id_hebergement INT NOT NULL, CHANGE date_debut date_debut DATE NOT NULL, CHANGE date_fin date_fin DATE NOT NULL, CHANGE date_reservation date_reservation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY reservation_hebergement_ibfk_1');
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY reservation_hebergement_ibfk_2');
        $this->addSql('ALTER TABLE reservation_hebergement CHANGE id_reservation_hebergement id_reservation_hebergement INT NOT NULL');
        $this->addSql('DROP INDEX id_patient ON reservation_hebergement');
        $this->addSql('CREATE INDEX IDX_843E00C0C4477E9B ON reservation_hebergement (id_patient)');
        $this->addSql('DROP INDEX id_hebergement ON reservation_hebergement');
        $this->addSql('CREATE INDEX IDX_843E00C05040106B ON reservation_hebergement (id_hebergement)');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT reservation_hebergement_ibfk_1 FOREIGN KEY (id_patient) REFERENCES utilisateur (id_utilisateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT reservation_hebergement_ibfk_2 FOREIGN KEY (id_hebergement) REFERENCES hebergement (id_hebergement) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_transport DROP FOREIGN KEY reservation_transport_ibfk_2');
        $this->addSql('ALTER TABLE reservation_transport DROP FOREIGN KEY reservation_transport_ibfk_1');
        $this->addSql('ALTER TABLE reservation_transport CHANGE id_reservation_transport id_reservation_transport INT NOT NULL, CHANGE heure_depart heure_depart VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX id_patient ON reservation_transport');
        $this->addSql('CREATE INDEX IDX_7CEC40B1C4477E9B ON reservation_transport (id_patient)');
        $this->addSql('DROP INDEX id_transport ON reservation_transport');
        $this->addSql('CREATE INDEX IDX_7CEC40B1E69E9D09 ON reservation_transport (id_transport)');
        $this->addSql('ALTER TABLE reservation_transport ADD CONSTRAINT reservation_transport_ibfk_2 FOREIGN KEY (id_transport) REFERENCES transport (id_transport) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_transport ADD CONSTRAINT reservation_transport_ibfk_1 FOREIGN KEY (id_patient) REFERENCES utilisateur (id_utilisateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role CHANGE id_role id_role INT NOT NULL');
        $this->addSql('ALTER TABLE specialite CHANGE id_specialite id_specialite INT NOT NULL');
        $this->addSql('ALTER TABLE transport CHANGE id_transport id_transport INT NOT NULL, CHANGE tarif tarif DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX email ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY utilisateur_ibfk_1');
        $this->addSql('ALTER TABLE utilisateur ADD reset_password_token VARCHAR(255) NOT NULL, DROP resetPasswordToken, CHANGE id_utilisateur id_utilisateur INT NOT NULL, CHANGE telephone telephone VARCHAR(20) NOT NULL, CHANGE date_naissance date_naissance DATE NOT NULL, CHANGE adresse adresse LONGTEXT NOT NULL, CHANGE image_url image_url VARCHAR(255) NOT NULL, CHANGE nationalite nationalite VARCHAR(100) NOT NULL');
        $this->addSql('DROP INDEX id_role ON utilisateur');
        $this->addSql('CREATE INDEX IDX_1D1C63B3DC499668 ON utilisateur (id_role)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT utilisateur_ibfk_1 FOREIGN KEY (id_role) REFERENCES role (id_role) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE accompagnateur CHANGE id_accompagnateur id_accompagnateur INT AUTO_INCREMENT NOT NULL, CHANGE photo_profil photo_profil VARCHAR(255) DEFAULT NULL, CHANGE experience experience TEXT DEFAULT NULL, CHANGE motivation motivation TEXT DEFAULT NULL, CHANGE langues langues JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE statut statut VARCHAR(255) DEFAULT \'en attente\', CHANGE date_recrutement date_recrutement DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE offre_emploi CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE description description TEXT NOT NULL, CHANGE date_publication date_publication DATE DEFAULT CURRENT_DATE NOT NULL');
        $this->addSql('ALTER TABLE paiement CHANGE id_reservation id_reservation INT DEFAULT NULL, CHANGE montant montant NUMERIC(10, 2) DEFAULT \'0.00\', CHANGE date_paiement date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE methode methode VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX paiement_ibfk_1 ON paiement (id_reservation)');
        $this->addSql('ALTER TABLE planning_accompagnateur DROP FOREIGN KEY FK_BDDF4B5D8492CD27');
        $this->addSql('ALTER TABLE planning_accompagnateur CHANGE id_planning id_planning INT AUTO_INCREMENT NOT NULL, CHANGE heure_debut heure_debut TIME NOT NULL, CHANGE heure_fin heure_fin TIME NOT NULL, CHANGE statut statut VARCHAR(255) DEFAULT \'disponible\'');
        $this->addSql('DROP INDEX idx_bddf4b5d8492cd27 ON planning_accompagnateur');
        $this->addSql('CREATE INDEX id_accompagnateur ON planning_accompagnateur (id_accompagnateur)');
        $this->addSql('ALTER TABLE planning_accompagnateur ADD CONSTRAINT FK_BDDF4B5D8492CD27 FOREIGN KEY (id_accompagnateur) REFERENCES accompagnateur (id_accompagnateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning_docteur DROP FOREIGN KEY FK_5ECAF9D25D3A0D49');
        $this->addSql('ALTER TABLE planning_docteur CHANGE id_planning id_planning INT AUTO_INCREMENT NOT NULL, CHANGE heure_debut heure_debut TIME NOT NULL, CHANGE heure_fin heure_fin TIME NOT NULL');
        $this->addSql('DROP INDEX idx_5ecaf9d25d3a0d49 ON planning_docteur');
        $this->addSql('CREATE INDEX id_docteur ON planning_docteur (id_docteur)');
        $this->addSql('ALTER TABLE planning_docteur ADD CONSTRAINT FK_5ECAF9D25D3A0D49 FOREIGN KEY (id_docteur) REFERENCES docteur (id_docteur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation CHANGE id_reservation id_reservation INT AUTO_INCREMENT NOT NULL, CHANGE id_clinique id_clinique INT DEFAULT NULL, CHANGE id_transport id_transport INT DEFAULT NULL, CHANGE date_depart date_depart DATE DEFAULT NULL, CHANGE heure_depart heure_depart VARCHAR(255) DEFAULT NULL, CHANGE id_hebergement id_hebergement INT DEFAULT NULL, CHANGE date_debut date_debut DATE DEFAULT NULL, CHANGE date_fin date_fin DATE DEFAULT NULL, CHANGE date_reservation date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C0C4477E9B');
        $this->addSql('ALTER TABLE reservation_hebergement DROP FOREIGN KEY FK_843E00C05040106B');
        $this->addSql('ALTER TABLE reservation_hebergement CHANGE id_reservation_hebergement id_reservation_hebergement INT AUTO_INCREMENT NOT NULL');
        $this->addSql('DROP INDEX idx_843e00c0c4477e9b ON reservation_hebergement');
        $this->addSql('CREATE INDEX id_patient ON reservation_hebergement (id_patient)');
        $this->addSql('DROP INDEX idx_843e00c05040106b ON reservation_hebergement');
        $this->addSql('CREATE INDEX id_hebergement ON reservation_hebergement (id_hebergement)');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C0C4477E9B FOREIGN KEY (id_patient) REFERENCES utilisateur (id_utilisateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_hebergement ADD CONSTRAINT FK_843E00C05040106B FOREIGN KEY (id_hebergement) REFERENCES hebergement (id_hebergement) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_transport DROP FOREIGN KEY FK_7CEC40B1C4477E9B');
        $this->addSql('ALTER TABLE reservation_transport DROP FOREIGN KEY FK_7CEC40B1E69E9D09');
        $this->addSql('ALTER TABLE reservation_transport CHANGE id_reservation_transport id_reservation_transport INT AUTO_INCREMENT NOT NULL, CHANGE heure_depart heure_depart TIME NOT NULL');
        $this->addSql('DROP INDEX idx_7cec40b1c4477e9b ON reservation_transport');
        $this->addSql('CREATE INDEX id_patient ON reservation_transport (id_patient)');
        $this->addSql('DROP INDEX idx_7cec40b1e69e9d09 ON reservation_transport');
        $this->addSql('CREATE INDEX id_transport ON reservation_transport (id_transport)');
        $this->addSql('ALTER TABLE reservation_transport ADD CONSTRAINT FK_7CEC40B1C4477E9B FOREIGN KEY (id_patient) REFERENCES utilisateur (id_utilisateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_transport ADD CONSTRAINT FK_7CEC40B1E69E9D09 FOREIGN KEY (id_transport) REFERENCES transport (id_transport) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role CHANGE id_role id_role INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE specialite CHANGE id_specialite id_specialite INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE transport CHANGE id_transport id_transport INT AUTO_INCREMENT NOT NULL, CHANGE tarif tarif NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3DC499668');
        $this->addSql('ALTER TABLE utilisateur ADD resetPasswordToken VARCHAR(255) DEFAULT NULL, DROP reset_password_token, CHANGE id_utilisateur id_utilisateur INT AUTO_INCREMENT NOT NULL, CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL, CHANGE adresse adresse TEXT DEFAULT NULL, CHANGE image_url image_url VARCHAR(255) DEFAULT NULL, CHANGE nationalite nationalite VARCHAR(100) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX email ON utilisateur (email)');
        $this->addSql('DROP INDEX idx_1d1c63b3dc499668 ON utilisateur');
        $this->addSql('CREATE INDEX id_role ON utilisateur (id_role)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3DC499668 FOREIGN KEY (id_role) REFERENCES role (id_role) ON DELETE CASCADE');
    }
}
