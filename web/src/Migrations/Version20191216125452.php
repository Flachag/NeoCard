<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191216125452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE carte (idCarte INT AUTO_INCREMENT NOT NULL, idCompte INT DEFAULT NULL, INDEX idCompte (idCompte), PRIMARY KEY(idCarte)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (idCompte INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, idUtil INT DEFAULT NULL, INDEX idUtil (idUtil), PRIMARY KEY(idCompte)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tpe (numTPE INT AUTO_INCREMENT NOT NULL, idCompte INT DEFAULT NULL, INDEX idCompte (idCompte), PRIMARY KEY(numTPE)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (emetteur INT DEFAULT NULL, recepteur INT DEFAULT NULL, numTransac INT AUTO_INCREMENT NOT NULL, typeTransac VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, montant INT NOT NULL, date DATE NOT NULL, INDEX recepteur (recepteur), INDEX emetteur (emetteur), PRIMARY KEY(numTransac)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (idUtil INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, dateNaiss DATE NOT NULL, mdp VARCHAR(255) NOT NULL, PRIMARY KEY(idUtil)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carte ADD CONSTRAINT FK_BAD4FFFDACE7FAFA FOREIGN KEY (idCompte) REFERENCES compte (idCompte)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260AD9BEC3D FOREIGN KEY (idUtil) REFERENCES utilisateur (idUtil)');
        $this->addSql('ALTER TABLE tpe ADD CONSTRAINT FK_BE5EF5BFACE7FAFA FOREIGN KEY (idCompte) REFERENCES compte (idCompte)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D152127D6 FOREIGN KEY (emetteur) REFERENCES compte (idCompte)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1FDE0DADF FOREIGN KEY (recepteur) REFERENCES compte (idCompte)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE carte DROP FOREIGN KEY FK_BAD4FFFDACE7FAFA');
        $this->addSql('ALTER TABLE tpe DROP FOREIGN KEY FK_BE5EF5BFACE7FAFA');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D152127D6');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1FDE0DADF');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260AD9BEC3D');
        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE tpe');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE utilisateur');
    }
}
