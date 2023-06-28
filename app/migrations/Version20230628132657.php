<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628132657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(191) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_article (file_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_595CB75D93CB796C (file_id), INDEX IDX_595CB75D7294869C (article_id), PRIMARY KEY(file_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file_article ADD CONSTRAINT FK_595CB75D93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE file_article ADD CONSTRAINT FK_595CB75D7294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_article DROP FOREIGN KEY FK_595CB75D93CB796C');
        $this->addSql('ALTER TABLE file_article DROP FOREIGN KEY FK_595CB75D7294869C');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE file_article');
    }
}
