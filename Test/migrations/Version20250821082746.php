<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250821082746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car CHANGE animal animal TINYINT(1) DEFAULT NULL, CHANGE smoke smoke TINYINT(1) DEFAULT NULL, CHANGE liscencePlate liscence_plate VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ride ADD car_id INT NOT NULL, CHANGE daparture_date departure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_9B3D7CD0C3C6F69F ON ride (car_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car CHANGE animal animal TINYINT(1) NOT NULL, CHANGE smoke smoke TINYINT(1) NOT NULL, CHANGE liscence_plate liscencePlate VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0C3C6F69F');
        $this->addSql('DROP INDEX IDX_9B3D7CD0C3C6F69F ON ride');
        $this->addSql('ALTER TABLE ride DROP car_id, CHANGE departure_date daparture_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
