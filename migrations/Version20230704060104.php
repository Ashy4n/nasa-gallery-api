<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704060104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE camera_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE photo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rover_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE camera (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE photo (id INT NOT NULL, rover_id INT NOT NULL, camera_id INT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_14B784182236175C ON photo (rover_id)');
        $this->addSql('CREATE INDEX IDX_14B78418B47685CD ON photo (camera_id)');
        $this->addSql('CREATE TABLE rover (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE rover_camera (rover_id INT NOT NULL, camera_id INT NOT NULL, PRIMARY KEY(rover_id, camera_id))');
        $this->addSql('CREATE INDEX IDX_2C1DCADB2236175C ON rover_camera (rover_id)');
        $this->addSql('CREATE INDEX IDX_2C1DCADBB47685CD ON rover_camera (camera_id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784182236175C FOREIGN KEY (rover_id) REFERENCES rover (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418B47685CD FOREIGN KEY (camera_id) REFERENCES camera (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rover_camera ADD CONSTRAINT FK_2C1DCADB2236175C FOREIGN KEY (rover_id) REFERENCES rover (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rover_camera ADD CONSTRAINT FK_2C1DCADBB47685CD FOREIGN KEY (camera_id) REFERENCES camera (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE camera_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE photo_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rover_id_seq CASCADE');
        $this->addSql('ALTER TABLE photo DROP CONSTRAINT FK_14B784182236175C');
        $this->addSql('ALTER TABLE photo DROP CONSTRAINT FK_14B78418B47685CD');
        $this->addSql('ALTER TABLE rover_camera DROP CONSTRAINT FK_2C1DCADB2236175C');
        $this->addSql('ALTER TABLE rover_camera DROP CONSTRAINT FK_2C1DCADBB47685CD');
        $this->addSql('DROP TABLE camera');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE rover');
        $this->addSql('DROP TABLE rover_camera');
    }
}
