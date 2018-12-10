<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181210102540 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player_event DROP FOREIGN KEY FK_84DC71E199E6F5DF');
        $this->addSql('CREATE TABLE format_event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, number_of_players INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_92589AE271F7E88B (event_id), INDEX IDX_92589AE2A76ED395 (user_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE player_event');
        $this->addSql('ALTER TABLE event CHANGE round_minutes round_minutes INT NOT NULL, CHANGE round_seconds round_seconds INT NOT NULL, CHANGE pause_minutes pause_minutes INT NOT NULL, CHANGE pause_seconds pause_seconds INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, firstname VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, phone_number VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_unicode_ci, mail VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE player_event (player_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_84DC71E199E6F5DF (player_id), INDEX IDX_84DC71E171F7E88B (event_id), PRIMARY KEY(player_id, event_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE player_event ADD CONSTRAINT FK_84DC71E171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_event ADD CONSTRAINT FK_84DC71E199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE format_event');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('ALTER TABLE event CHANGE round_minutes round_minutes INT DEFAULT NULL, CHANGE round_seconds round_seconds INT DEFAULT NULL, CHANGE pause_minutes pause_minutes INT DEFAULT NULL, CHANGE pause_seconds pause_seconds INT DEFAULT NULL');
    }
}
