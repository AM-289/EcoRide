<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250820135318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ride ADD brand_id INT NOT NULL');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD044F5D008 FOREIGN KEY (brand_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_9B3D7CD044F5D008 ON ride (brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD044F5D008');
        $this->addSql('DROP INDEX IDX_9B3D7CD044F5D008 ON ride');
        $this->addSql('ALTER TABLE ride DROP brand_id');
    }
}
