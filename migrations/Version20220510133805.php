<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220510133805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_p (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category CHANGE category_id category_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE chat CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandeprod CHANGE id_produit id_produit INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY category');
        $this->addSql('ALTER TABLE game CHANGE category category INT DEFAULT NULL, CHANGE rate rate DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C64C19C1 FOREIGN KEY (category) REFERENCES category (category_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit2 ADD CONSTRAINT FK_BFF6AE8A58E019E5 FOREIGN KEY (category_p_id) REFERENCES category_p (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit2 DROP FOREIGN KEY FK_BFF6AE8A58E019E5');
        $this->addSql('DROP TABLE category_p');
        $this->addSql('ALTER TABLE category CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE chat CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE commandeprod CHANGE id_produit id_produit INT NOT NULL');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C64C19C1');
        $this->addSql('ALTER TABLE game CHANGE category category INT NOT NULL, CHANGE rate rate DOUBLE PRECISION DEFAULT \'0\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT category FOREIGN KEY (category) REFERENCES category (category_id)');
    }
}
