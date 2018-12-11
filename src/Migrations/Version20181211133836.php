<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181211133836 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player_event (player_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_84DC71E199E6F5DF (player_id), INDEX IDX_84DC71E171F7E88B (event_id), PRIMARY KEY(player_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_event ADD CONSTRAINT FK_84DC71E199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_event ADD CONSTRAINT FK_84DC71E171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3752C7DF5E237E06 ON table_event (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24344DAA85C8C9E4 ON format_event (number_of_players)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE player_event');
        $this->addSql('DROP INDEX UNIQ_24344DAA85C8C9E4 ON format_event');
        $this->addSql('DROP INDEX UNIQ_3752C7DF5E237E06 ON table_event');
    }
}
