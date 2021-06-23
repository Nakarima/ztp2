<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623164934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE statuses (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bugs ADD status_id INT NOT NULL, CHANGE author_id author_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE bugs ADD CONSTRAINT FK_1E197C96BF700BD FOREIGN KEY (status_id) REFERENCES statuses (id)');
        $this->addSql('CREATE INDEX IDX_1E197C96BF700BD ON bugs (status_id)');
        $this->addSql('ALTER TABLE categories CHANGE author_id author_id INT UNSIGNED NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bugs DROP FOREIGN KEY FK_1E197C96BF700BD');
        $this->addSql('DROP TABLE statuses');
        $this->addSql('DROP INDEX IDX_1E197C96BF700BD ON bugs');
        $this->addSql('ALTER TABLE bugs DROP status_id, CHANGE author_id author_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE categories CHANGE author_id author_id INT UNSIGNED DEFAULT NULL');
    }
}
