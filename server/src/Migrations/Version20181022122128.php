<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181022122128 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D197CD2A32');
        $this->addSql('DROP TABLE trading');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('DROP INDEX fk_transaction_trading1_idx ON transaction');
        $this->addSql('DROP INDEX fk_transaction_user1_idx ON transaction');
        $this->addSql('ALTER TABLE transaction ADD from_user_id INT DEFAULT NULL, ADD to_user_id INT DEFAULT NULL, ADD project_id INT DEFAULT NULL, ADD project_participation_id INT DEFAULT NULL, ADD nb_tokens NUMERIC(8, 2) NOT NULL, ADD nb_sdg NUMERIC(8, 2) NOT NULL, DROP trading_id, DROP user_id, CHANGE movement_type transaction_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D12130303A FOREIGN KEY (from_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D129F6EE60 FOREIGN KEY (to_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1166D1F9C FOREIGN KEY (project_id) REFERENCES git_project (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D74BF81A FOREIGN KEY (project_participation_id) REFERENCES project_participation (id)');
        $this->addSql('CREATE INDEX IDX_723705D1166D1F9C ON transaction (project_id)');
        $this->addSql('CREATE INDEX IDX_723705D1D74BF81A ON transaction (project_participation_id)');
        $this->addSql('CREATE INDEX fk_transaction_user2_idx ON transaction (to_user_id)');
        $this->addSql('CREATE INDEX fk_transaction_user1_idx ON transaction (from_user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE trading (id INT AUTO_INCREMENT NOT NULL, sell_offer_id INT DEFAULT NULL, transaction_utc_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, number_of_tokens NUMERIC(8, 2) NOT NULL, INDEX fk_trading_sell_offer1_idx (sell_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trading ADD CONSTRAINT FK_BC19FB58B2A188F1 FOREIGN KEY (sell_offer_id) REFERENCES sell_offer (id)');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D12130303A');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D129F6EE60');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1166D1F9C');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D74BF81A');
        $this->addSql('DROP INDEX IDX_723705D1166D1F9C ON transaction');
        $this->addSql('DROP INDEX IDX_723705D1D74BF81A ON transaction');
        $this->addSql('DROP INDEX fk_transaction_user2_idx ON transaction');
        $this->addSql('DROP INDEX fk_transaction_user1_idx ON transaction');
        $this->addSql('ALTER TABLE transaction ADD trading_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP from_user_id, DROP to_user_id, DROP project_id, DROP project_participation_id, DROP nb_tokens, DROP nb_sdg, CHANGE transaction_label movement_type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D197CD2A32 FOREIGN KEY (trading_id) REFERENCES trading (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX fk_transaction_trading1_idx ON transaction (trading_id)');
        $this->addSql('CREATE INDEX fk_transaction_user1_idx ON transaction (user_id)');
    }
}
