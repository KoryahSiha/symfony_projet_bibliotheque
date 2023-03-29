<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230319184921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, date_emprunt DATETIME NOT NULL, date_retour DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunteur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(190) NOT NULL, prenom VARCHAR(190) NOT NULL, tel VARCHAR(190) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(190) NOT NULL, annee_edition INT DEFAULT NULL, nombre_pages INT NOT NULL, code_isbn VARCHAR(190) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE emprunteur');
        $this->addSql('DROP TABLE livre');
    }
}
