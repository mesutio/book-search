<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617184749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, author_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(255) DEFAULT NULL, page_count INT NOT NULL, date DATE NOT NULL, thumbnail_url VARCHAR(255) DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, long_description LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, price_amount INT NOT NULL, price_currency_code VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_categories (book_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_A55E0CDB16A2B381 (book_id), INDEX IDX_A55E0CDB12469DE2 (category_id), PRIMARY KEY(book_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_authors (book_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_1D2C02C716A2B381 (book_id), INDEX IDX_1D2C02C7F675F31B (author_id), PRIMARY KEY(book_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, category_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_categories ADD CONSTRAINT FK_A55E0CDB16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE book_categories ADD CONSTRAINT FK_A55E0CDB12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE book_authors ADD CONSTRAINT FK_1D2C02C716A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE book_authors ADD CONSTRAINT FK_1D2C02C7F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_categories DROP FOREIGN KEY FK_A55E0CDB16A2B381');
        $this->addSql('ALTER TABLE book_categories DROP FOREIGN KEY FK_A55E0CDB12469DE2');
        $this->addSql('ALTER TABLE book_authors DROP FOREIGN KEY FK_1D2C02C716A2B381');
        $this->addSql('ALTER TABLE book_authors DROP FOREIGN KEY FK_1D2C02C7F675F31B');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE book_categories');
        $this->addSql('DROP TABLE book_authors');
        $this->addSql('DROP TABLE categories');
    }
}
