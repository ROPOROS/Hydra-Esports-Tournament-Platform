<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507125659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, idTournoi INT NOT NULL, etat VARCHAR(255) NOT NULL, dateMatch DATE NOT NULL, score VARCHAR(255) NOT NULL, heureMatch INT NOT NULL, idEquipeA INT NOT NULL, idEquipeB INT NOT NULL, phase INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE commande CHANGE idUser idUser INT DEFAULT NULL, CHANGE idProduit idProduit INT DEFAULT NULL, CHANGE confirme confirme INT NOT NULL');
        $this->addSql('ALTER TABLE donation CHANGE idUser idUser INT DEFAULT NULL, CHANGE idTeam idTeam INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invitation CHANGE idcaptain idcaptain INT DEFAULT NULL, CHANGE idjoueur idjoueur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news CHANGE idJeu idJeu INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pari DROP FOREIGN KEY pari_ibfk_1');
        $this->addSql('DROP INDEX iduser ON pari');
        $this->addSql('CREATE INDEX IDX_2A091C1FFE6E88D7 ON pari (idUser)');
        $this->addSql('ALTER TABLE pari ADD CONSTRAINT pari_ibfk_1 FOREIGN KEY (idUser) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE produit CHANGE idEquipe idEquipe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tmatchs CHANGE idTournoi idTournoi INT DEFAULT NULL, CHANGE idEquipeA idEquipeA INT DEFAULT NULL, CHANGE idEquipeB idEquipeB INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tournoi CHANGE idJeu idJeu INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `match`');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE commande CHANGE confirme confirme INT DEFAULT 0 NOT NULL, CHANGE idUser idUser INT NOT NULL, CHANGE idProduit idProduit INT NOT NULL');
        $this->addSql('ALTER TABLE donation CHANGE idUser idUser INT NOT NULL, CHANGE idTeam idTeam INT NOT NULL');
        $this->addSql('ALTER TABLE invitation CHANGE idjoueur idjoueur INT NOT NULL, CHANGE idcaptain idcaptain INT NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE idJeu idJeu INT NOT NULL');
        $this->addSql('ALTER TABLE pari DROP FOREIGN KEY FK_2A091C1FFE6E88D7');
        $this->addSql('DROP INDEX idx_2a091c1ffe6e88d7 ON pari');
        $this->addSql('CREATE INDEX idUser ON pari (idUser)');
        $this->addSql('ALTER TABLE pari ADD CONSTRAINT FK_2A091C1FFE6E88D7 FOREIGN KEY (idUser) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE produit CHANGE idEquipe idEquipe INT NOT NULL');
        $this->addSql('ALTER TABLE tmatchs CHANGE idTournoi idTournoi INT NOT NULL, CHANGE idEquipeB idEquipeB INT NOT NULL, CHANGE idEquipeA idEquipeA INT NOT NULL');
        $this->addSql('ALTER TABLE tournoi CHANGE idJeu idJeu INT NOT NULL');
    }
}
