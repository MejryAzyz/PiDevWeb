<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408191209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation_accompagnateur DROP FOREIGN KEY FK_C7B9A725ADA84A2');
        $this->addSql('ALTER TABLE affectation_accompagnateur DROP FOREIGN KEY FK_C7B9A728492CD27');
        $this->addSql('ALTER TABLE affectation_accompagnateur CHANGE id_affectation id_affectation INT NOT NULL');
        $this->addSql('ALTER TABLE affectation_accompagnateur ADD CONSTRAINT FK_C7B9A725ADA84A2 FOREIGN KEY (id_reservation) REFERENCES reservation (id_reservation) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE affectation_accompagnateur ADD CONSTRAINT FK_C7B9A728492CD27 FOREIGN KEY (id_accompagnateur) REFERENCES accompagnateur (id_accompagnateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offreemploi CHANGE id id BIGINT NOT NULL, CHANGE datepublication datepublication DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE affectation_accompagnateur DROP FOREIGN KEY FK_C7B9A728492CD27');
        $this->addSql('ALTER TABLE affectation_accompagnateur DROP FOREIGN KEY FK_C7B9A725ADA84A2');
        $this->addSql('ALTER TABLE affectation_accompagnateur CHANGE id_affectation id_affectation INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE affectation_accompagnateur ADD CONSTRAINT FK_C7B9A728492CD27 FOREIGN KEY (id_accompagnateur) REFERENCES accompagnateur (id_accompagnateur)');
        $this->addSql('ALTER TABLE affectation_accompagnateur ADD CONSTRAINT FK_C7B9A725ADA84A2 FOREIGN KEY (id_reservation) REFERENCES reservation (id_reservation)');
        $this->addSql('ALTER TABLE offreemploi CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE datepublication datepublication DATE NOT NULL');
    }
}
