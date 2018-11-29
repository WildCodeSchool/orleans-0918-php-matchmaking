<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181129085426 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE table_event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE round_event (id INT AUTO_INCREMENT NOT NULL, format_event_id INT NOT NULL, table_event_id INT NOT NULL, speech_turn INT NOT NULL, speaker INT NOT NULL, INDEX IDX_EDDD1E5ED6CDD85F (format_event_id), INDEX IDX_EDDD1E5EE14AC65B (table_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE round_event ADD CONSTRAINT FK_EDDD1E5ED6CDD85F FOREIGN KEY (format_event_id) REFERENCES format_event (id)');
        $this->addSql('ALTER TABLE round_event ADD CONSTRAINT FK_EDDD1E5EE14AC65B FOREIGN KEY (table_event_id) REFERENCES table_event (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE round_event DROP FOREIGN KEY FK_EDDD1E5EE14AC65B');
        $this->addSql('DROP TABLE table_event');
        $this->addSql('DROP TABLE round_event');
    }
}
