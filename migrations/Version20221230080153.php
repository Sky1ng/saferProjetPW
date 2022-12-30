<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230080153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_sent_bien DROP FOREIGN KEY FK_F081236DBD95B80F');
        $this->addSql('ALTER TABLE favoris_sent_bien DROP FOREIGN KEY FK_F081236DF660C0E1');
        $this->addSql('DROP TABLE favoris_sent_bien');
        $this->addSql('ALTER TABLE favoris_sent ADD biens LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris_sent_bien (favoris_sent_id INT NOT NULL, bien_id INT NOT NULL, INDEX IDX_F081236DF660C0E1 (favoris_sent_id), INDEX IDX_F081236DBD95B80F (bien_id), PRIMARY KEY(favoris_sent_id, bien_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favoris_sent_bien ADD CONSTRAINT FK_F081236DBD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_sent_bien ADD CONSTRAINT FK_F081236DF660C0E1 FOREIGN KEY (favoris_sent_id) REFERENCES favoris_sent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_sent DROP biens');
    }
}
