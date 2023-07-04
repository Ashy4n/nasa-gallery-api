<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704082402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE camera ADD full_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rover ADD min_date DATE NOT NULL');
        $this->addSql('ALTER TABLE rover ADD max_date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE camera DROP full_name');
        $this->addSql('ALTER TABLE rover DROP min_date');
        $this->addSql('ALTER TABLE rover DROP max_date');
    }
}
