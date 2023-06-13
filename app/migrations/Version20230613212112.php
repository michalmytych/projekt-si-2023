<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613212112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles_tags (article_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_354053617294869C (article_id), INDEX IDX_35405361BAD26311 (tag_id), PRIMARY KEY(article_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles_tags ADD CONSTRAINT FK_354053617294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_tags ADD CONSTRAINT FK_35405361BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F97294869C');
        $this->addSql('ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F9BAD26311');
        $this->addSql('DROP TABLE article_tag');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_tag (article_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_919694F9BAD26311 (tag_id), INDEX IDX_919694F97294869C (article_id), PRIMARY KEY(article_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F97294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F9BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_tags DROP FOREIGN KEY FK_354053617294869C');
        $this->addSql('ALTER TABLE articles_tags DROP FOREIGN KEY FK_35405361BAD26311');
        $this->addSql('DROP TABLE articles_tags');
    }
}
