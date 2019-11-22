<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191122112037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tagging (id INT AUTO_INCREMENT NOT NULL, tag_id INT DEFAULT NULL, resource_type VARCHAR(50) NOT NULL, resource_id VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A4AED123BAD26311 (tag_id), UNIQUE INDEX tagging_idx (tag_id, resource_type, resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_389B7835E237E06 (name), UNIQUE INDEX UNIQ_389B783989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, markdown_body LONGTEXT NOT NULL, language VARCHAR(2) NOT NULL, published_at DATETIME NOT NULL, event_url LONGTEXT DEFAULT NULL, event_date DATETIME DEFAULT NULL, address LONGTEXT NOT NULL, map LONGTEXT NOT NULL, city VARCHAR(255) NOT NULL, is_published TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Article_Author (article_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_1DA032A67294869C (article_id), INDEX IDX_1DA032A6F675F31B (author_id), PRIMARY KEY(article_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tagging ADD CONSTRAINT FK_A4AED123BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE Article_Author ADD CONSTRAINT FK_1DA032A67294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Article_Author ADD CONSTRAINT FK_1DA032A6F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tagging DROP FOREIGN KEY FK_A4AED123BAD26311');
        $this->addSql('ALTER TABLE Article_Author DROP FOREIGN KEY FK_1DA032A6F675F31B');
        $this->addSql('ALTER TABLE Article_Author DROP FOREIGN KEY FK_1DA032A67294869C');
        $this->addSql('DROP TABLE tagging');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE Article_Author');
    }
}
