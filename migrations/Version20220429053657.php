<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220429053657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recomendedg ADD id_category INT DEFAULT NULL, DROP category');
        $this->addSql('ALTER TABLE recomendedg ADD CONSTRAINT FK_2A67D3BB5697F554 FOREIGN KEY (id_category) REFERENCES category (category_id)');
        $this->addSql('CREATE INDEX IDX_2A67D3BB5697F554 ON recomendedg (id_category)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recomendedg DROP FOREIGN KEY FK_2A67D3BB5697F554');
        $this->addSql('DROP INDEX IDX_2A67D3BB5697F554 ON recomendedg');
        $this->addSql('ALTER TABLE recomendedg ADD category VARCHAR(255) NOT NULL, DROP id_category');
    }
}
