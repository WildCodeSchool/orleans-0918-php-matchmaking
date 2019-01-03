<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190103104819 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE status_event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, state INT NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE table_event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3752C7DF5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE society (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, phone_number VARCHAR(10) DEFAULT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_event (player_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_84DC71E199E6F5DF (player_id), INDEX IDX_84DC71E171F7E88B (event_id), PRIMARY KEY(player_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, society_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, activated TINYINT(1) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649E6389D24 (society_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE format_event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, number_of_players INT NOT NULL, UNIQUE INDEX UNIQ_24344DAA85C8C9E4 (number_of_players), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timer (id INT AUTO_INCREMENT NOT NULL, round_minutes INT NOT NULL, round_seconds INT DEFAULT NULL, pause_minutes INT DEFAULT NULL, pause_seconds INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE round_event (id INT AUTO_INCREMENT NOT NULL, format_event_id INT NOT NULL, table_event_id INT NOT NULL, speech_turn INT NOT NULL, speaker INT NOT NULL, INDEX IDX_EDDD1E5ED6CDD85F (format_event_id), INDEX IDX_EDDD1E5EE14AC65B (table_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, format_event_id INT NOT NULL, status_event_id INT NOT NULL, society_id INT NOT NULL, title VARCHAR(255) NOT NULL, date DATETIME NOT NULL, description LONGTEXT NOT NULL, round_minutes INT NOT NULL, round_seconds INT NOT NULL, pause_minutes INT NOT NULL, pause_seconds INT NOT NULL, logo VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3BAE0AA7D6CDD85F (format_event_id), INDEX IDX_3BAE0AA7EC2D4F53 (status_event_id), INDEX IDX_3BAE0AA7E6389D24 (society_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_event ADD CONSTRAINT FK_84DC71E199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_event ADD CONSTRAINT FK_84DC71E171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E6389D24 FOREIGN KEY (society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE round_event ADD CONSTRAINT FK_EDDD1E5ED6CDD85F FOREIGN KEY (format_event_id) REFERENCES format_event (id)');
        $this->addSql('ALTER TABLE round_event ADD CONSTRAINT FK_EDDD1E5EE14AC65B FOREIGN KEY (table_event_id) REFERENCES table_event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D6CDD85F FOREIGN KEY (format_event_id) REFERENCES format_event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7EC2D4F53 FOREIGN KEY (status_event_id) REFERENCES status_event (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7E6389D24 FOREIGN KEY (society_id) REFERENCES society (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7EC2D4F53');
        $this->addSql('ALTER TABLE round_event DROP FOREIGN KEY FK_EDDD1E5EE14AC65B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E6389D24');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7E6389D24');
        $this->addSql('ALTER TABLE player_event DROP FOREIGN KEY FK_84DC71E199E6F5DF');
        $this->addSql('ALTER TABLE round_event DROP FOREIGN KEY FK_EDDD1E5ED6CDD85F');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7D6CDD85F');
        $this->addSql('ALTER TABLE player_event DROP FOREIGN KEY FK_84DC71E171F7E88B');
        $this->addSql('DROP TABLE status_event');
        $this->addSql('DROP TABLE table_event');
        $this->addSql('DROP TABLE society');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_event');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE format_event');
        $this->addSql('DROP TABLE timer');
        $this->addSql('DROP TABLE round_event');
        $this->addSql('DROP TABLE event');
    }
}
