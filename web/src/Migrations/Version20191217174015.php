<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191217174015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE carte CHANGE idCompte idCompte INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account CHANGE idUser idUtil INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tpe CHANGE idCompte idCompte INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD login VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE carte CHANGE idCompte idCompte INT NOT NULL');
        $this->addSql('ALTER TABLE account CHANGE idUser idUtil INT NOT NULL');
        $this->addSql('ALTER TABLE tpe CHANGE idCompte idCompte INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP username');
    }
}
