<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511113436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE membre (username VARCHAR(30) NOT NULL, equipes_id VARCHAR(50) NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, email VARCHAR(50) NOT NULL, INDEX IDX_F6B4FB29737800BA (equipes_id), PRIMARY KEY(username)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB29737800BA FOREIGN KEY (equipes_id) REFERENCES equipe (nom_equipe)');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E604150E777D5');
        $this->addSql('DROP INDEX IDX_6B1E604150E777D5 ON matchs');
        $this->addSql('ALTER TABLE matchs ADD poule VARCHAR(255) NOT NULL, DROP nom_poule');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E6041FA1FEB40 FOREIGN KEY (poule) REFERENCES poule (nom_poule)');
        $this->addSql('CREATE INDEX IDX_6B1E6041FA1FEB40 ON matchs (poule)');
        $this->addSql('ALTER TABLE poule CHANGE nom_poule nom_poule VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tournoi DROP id_user, CHANGE nom_t nom_t VARCHAR(255) NOT NULL, CHANGE emplacement_t emplacement_t VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_18AFD9DFA92175A9 ON tournoi (nom_t)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE membre');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E6041FA1FEB40');
        $this->addSql('DROP INDEX IDX_6B1E6041FA1FEB40 ON matchs');
        $this->addSql('ALTER TABLE matchs ADD nom_poule VARCHAR(50) NOT NULL, DROP poule');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E604150E777D5 FOREIGN KEY (nom_poule) REFERENCES poule (nom_poule)');
        $this->addSql('CREATE INDEX IDX_6B1E604150E777D5 ON matchs (nom_poule)');
        $this->addSql('ALTER TABLE poule CHANGE nom_poule nom_poule VARCHAR(50) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_18AFD9DFA92175A9 ON tournoi');
        $this->addSql('ALTER TABLE tournoi ADD id_user INT NOT NULL, CHANGE nom_t nom_t VARCHAR(50) NOT NULL, CHANGE emplacement_t emplacement_t VARCHAR(50) NOT NULL');
    }
}
