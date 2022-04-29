<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429052443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE rec_games');
        $this->addSql('ALTER TABLE commentaire ADD iduser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id_user)');
        $this->addSql('CREATE INDEX IDX_67F068BC5E5C27E9 ON commentaire (iduser)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rec_games (id_rg INT AUTO_INCREMENT NOT NULL, nom _rg VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, category_rg VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, platform_rg VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, url_rg VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, prix_rg DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_rg)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = MyISAM COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5E5C27E9');
        $this->addSql('DROP INDEX IDX_67F068BC5E5C27E9 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP iduser');
    }
}
