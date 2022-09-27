<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220926144804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_topic (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, category_id INT DEFAULT NULL, INDEX IDX_757391521F55203D (topic_id), INDEX IDX_7573915212469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_topic ADD CONSTRAINT FK_757391521F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE category_topic ADD CONSTRAINT FK_7573915212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE comment_topic DROP FOREIGN KEY FK_34DA46EA12469DE2');
        $this->addSql('ALTER TABLE comment_topic DROP FOREIGN KEY FK_34DA46EA1F55203D');
        $this->addSql('DROP TABLE comment_topic');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_topic (id INT AUTO_INCREMENT NOT NULL, topic_id INT DEFAULT NULL, category_id INT DEFAULT NULL, INDEX IDX_34DA46EA12469DE2 (category_id), INDEX IDX_34DA46EA1F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment_topic ADD CONSTRAINT FK_34DA46EA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE comment_topic ADD CONSTRAINT FK_34DA46EA1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE category_topic DROP FOREIGN KEY FK_757391521F55203D');
        $this->addSql('ALTER TABLE category_topic DROP FOREIGN KEY FK_7573915212469DE2');
        $this->addSql('DROP TABLE category_topic');
    }
}
