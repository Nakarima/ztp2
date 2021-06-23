<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623185306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bugs_tags (bug_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_47303C5CFA3DB3D5 (bug_id), INDEX IDX_47303C5CBAD26311 (tag_id), PRIMARY KEY(bug_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(64) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, code VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bugs_tags ADD CONSTRAINT FK_47303C5CFA3DB3D5 FOREIGN KEY (bug_id) REFERENCES bugs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bugs_tags ADD CONSTRAINT FK_47303C5CBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bugs_tags DROP FOREIGN KEY FK_47303C5CBAD26311');
        $this->addSql('DROP TABLE bugs_tags');
        $this->addSql('DROP TABLE tags');
    }
}
