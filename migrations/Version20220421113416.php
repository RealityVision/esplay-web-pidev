<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421113416 extends AbstractMigration
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
        $this->addSql('ALTER TABLE report CHANGE id_message id_message INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_sender id_sender INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE chat CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE commandeprod CHANGE id_produit id_produit INT NOT NULL');
        $this->addSql('ALTER TABLE game CHANGE category category INT NOT NULL, CHANGE rate rate DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE report CHANGE id_message id_message INT NOT NULL, CHANGE id_user id_user INT NOT NULL, CHANGE id_sender id_sender INT NOT NULL');
    }
}
