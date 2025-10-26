<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509163620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY fk_donation_user');
        $this->addSql('ALTER TABLE donation DROP FOREIGN KEY fk_donation_team');
        $this->addSql('DROP INDEX fk_donation_user ON donation');
        $this->addSql('DROP INDEX fk_donation_team ON donation');
        $this->addSql('ALTER TABLE donation CHANGE idUser idUser INT NOT NULL, CHANGE idTeam idTeam INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation CHANGE idUser idUser INT DEFAULT NULL, CHANGE idTeam idTeam INT DEFAULT NULL');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT fk_donation_user FOREIGN KEY (idUser) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE donation ADD CONSTRAINT fk_donation_team FOREIGN KEY (idTeam) REFERENCES team (ID)');
        $this->addSql('CREATE INDEX fk_donation_user ON donation (idUser)');
        $this->addSql('CREATE INDEX fk_donation_team ON donation (idTeam)');
    }
}
