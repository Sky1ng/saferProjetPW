<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104202511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_sent ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris_sent ADD CONSTRAINT FK_943B0CE642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_943B0CE642B8210 ON favoris_sent (admin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_sent DROP FOREIGN KEY FK_943B0CE642B8210');
        $this->addSql('DROP INDEX IDX_943B0CE642B8210 ON favoris_sent');
        $this->addSql('ALTER TABLE favoris_sent DROP admin_id');
    }
}
