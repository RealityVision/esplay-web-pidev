<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510131137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE category_id category_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE chat CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandeprod CHANGE id_produit id_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game CHANGE category category INT DEFAULT NULL, CHANGE rate rate DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE produit2 RENAME INDEX category_p_id TO IDX_BFF6AE8A58E019E5');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE chat CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE commandeprod CHANGE id_produit id_produit INT NOT NULL');
        $this->addSql('ALTER TABLE game CHANGE category category INT NOT NULL, CHANGE rate rate DOUBLE PRECISION DEFAULT \'0\'');
        $this->addSql('ALTER TABLE produit2 RENAME INDEX idx_bff6ae8a58e019e5 TO category_p_id');
    }
}
