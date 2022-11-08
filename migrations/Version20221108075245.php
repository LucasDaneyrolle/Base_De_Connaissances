<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221108075245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_form (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, form_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, INDEX IDX_74BE5FA3A76ED395 (user_id), INDEX IDX_74BE5FA35FF69B7D (form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, problem LONGTEXT NOT NULL, solution LONGTEXT NOT NULL, state INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5288FD4FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE form_category (form_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_B7769C855FF69B7D (form_id), INDEX IDX_B7769C8512469DE2 (category_id), PRIMARY KEY(form_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE response_topic (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, INDEX IDX_8D36054BA76ED395 (user_id), INDEX IDX_8D36054B1F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, state INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_category (topic_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_F07D94C71F55203D (topic_id), INDEX IDX_F07D94C712469DE2 (category_id), PRIMARY KEY(topic_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_form ADD CONSTRAINT FK_74BE5FA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment_form ADD CONSTRAINT FK_74BE5FA35FF69B7D FOREIGN KEY (form_id) REFERENCES form (id)');
        $this->addSql('ALTER TABLE form ADD CONSTRAINT FK_5288FD4FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE form_category ADD CONSTRAINT FK_B7769C855FF69B7D FOREIGN KEY (form_id) REFERENCES form (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE form_category ADD CONSTRAINT FK_B7769C8512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE response_topic ADD CONSTRAINT FK_8D36054BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE response_topic ADD CONSTRAINT FK_8D36054B1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('ALTER TABLE topic_category ADD CONSTRAINT FK_F07D94C71F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_category ADD CONSTRAINT FK_F07D94C712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_form DROP FOREIGN KEY FK_74BE5FA3A76ED395');
        $this->addSql('ALTER TABLE comment_form DROP FOREIGN KEY FK_74BE5FA35FF69B7D');
        $this->addSql('ALTER TABLE form DROP FOREIGN KEY FK_5288FD4FA76ED395');
        $this->addSql('ALTER TABLE form_category DROP FOREIGN KEY FK_B7769C855FF69B7D');
        $this->addSql('ALTER TABLE form_category DROP FOREIGN KEY FK_B7769C8512469DE2');
        $this->addSql('ALTER TABLE response_topic DROP FOREIGN KEY FK_8D36054BA76ED395');
        $this->addSql('ALTER TABLE response_topic DROP FOREIGN KEY FK_8D36054B1F55203D');
        $this->addSql('ALTER TABLE topic_category DROP FOREIGN KEY FK_F07D94C71F55203D');
        $this->addSql('ALTER TABLE topic_category DROP FOREIGN KEY FK_F07D94C712469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment_form');
        $this->addSql('DROP TABLE form');
        $this->addSql('DROP TABLE form_category');
        $this->addSql('DROP TABLE response_topic');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE topic_category');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
