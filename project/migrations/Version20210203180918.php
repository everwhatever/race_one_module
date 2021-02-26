<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203180918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE time (id INT AUTO_INCREMENT NOT NULL, drivers_id INT DEFAULT NULL, races_id INT DEFAULT NULL, time TIME NOT NULL, INDEX IDX_6F9498459E6E47B8 (drivers_id), INDEX IDX_6F94984599AE984C (races_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE time ADD CONSTRAINT FK_6F9498459E6E47B8 FOREIGN KEY (drivers_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE time ADD CONSTRAINT FK_6F94984599AE984C FOREIGN KEY (races_id) REFERENCES race (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE time');
    }
}