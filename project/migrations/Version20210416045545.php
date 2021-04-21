<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210416045545 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, positions LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE league_driver (league_id INT NOT NULL, driver_id INT NOT NULL, INDEX IDX_DEAB04AF58AFC4DE (league_id), INDEX IDX_DEAB04AFC3423909 (driver_id), PRIMARY KEY(league_id, driver_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE league_driver ADD CONSTRAINT FK_DEAB04AF58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE league_driver ADD CONSTRAINT FK_DEAB04AFC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE race ADD league_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('CREATE INDEX IDX_DA6FBBAF58AFC4DE ON race (league_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE league_driver DROP FOREIGN KEY FK_DEAB04AF58AFC4DE');
        $this->addSql('ALTER TABLE race DROP FOREIGN KEY FK_DA6FBBAF58AFC4DE');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE league_driver');
        $this->addSql('DROP INDEX IDX_DA6FBBAF58AFC4DE ON race');
        $this->addSql('ALTER TABLE race DROP league_id');
    }
}
