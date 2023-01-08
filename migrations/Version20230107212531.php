<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230107212531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bien (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, localisation INT NOT NULL, surface VARCHAR(255) NOT NULL, prix INT NOT NULL, type VARCHAR(255) NOT NULL, image VARCHAR(500) DEFAULT NULL, INDEX IDX_45EDC3869F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, biens LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_form (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, prix VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, surface VARCHAR(255) DEFAULT NULL, categorie VARCHAR(255) DEFAULT NULL, keyword VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris_sent (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, biens LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', date DATE NOT NULL, INDEX IDX_943B0CE642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3869F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE favoris_sent ADD CONSTRAINT FK_943B0CE642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC3869F34925F');
        $this->addSql('ALTER TABLE favoris_sent DROP FOREIGN KEY FK_943B0CE642B8210');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE bien');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE contact_form');
        $this->addSql('DROP TABLE favoris_sent');
    }
}
