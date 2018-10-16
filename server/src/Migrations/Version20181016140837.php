<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016140837 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project_participation (id INT AUTO_INCREMENT NOT NULL, git_project_id INT DEFAULT NULL, number_of_tokens NUMERIC(8, 2) NOT NULL, calculation_utc_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, commit_id VARCHAR(45) NOT NULL, INDEX fk_project_participation_git_project1_idx (git_project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(45) NOT NULL, github_id VARCHAR(45) NOT NULL, timezone VARCHAR(45) NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', access_token VARCHAR(255) NOT NULL, black_listed TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649D4327649 (github_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_offer (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, purchaser_id INT DEFAULT NULL, number_of_tokens NUMERIC(6, 2) NOT NULL, purchase_price_per_token NUMERIC(6, 2) NOT NULL, offer_starts_utc_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, offer_expires_at_utc_date DATETIME DEFAULT NULL, INDEX fk_trading_user_idx (purchaser_id), INDEX fk_purchase_offer_project1_idx (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE git_project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, git_address VARCHAR(120) NOT NULL, project_address VARCHAR(120) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trading (id INT AUTO_INCREMENT NOT NULL, sell_offer_id INT DEFAULT NULL, transaction_utc_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, number_of_tokens NUMERIC(8, 2) NOT NULL, INDEX fk_trading_sell_offer1_idx (sell_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sell_offer (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, seller_id INT DEFAULT NULL, number_of_tokens NUMERIC(6, 2) NOT NULL, sell_price_per_token NUMERIC(6, 2) NOT NULL, offer_starts_utc_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, offer_expires_at_utc_date DATETIME DEFAULT NULL, INDEX fk_trading_user_idx (seller_id), INDEX fk_sell_offer_project1_idx (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, project_participation_id INT DEFAULT NULL, trading_id INT DEFAULT NULL, user_id INT DEFAULT NULL, movement_type VARCHAR(255) NOT NULL, INDEX fk_transaction_project_participation1_idx (project_participation_id), INDEX fk_transaction_trading1_idx (trading_id), INDEX fk_transaction_user1_idx (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_participation ADD CONSTRAINT FK_7FC475498820681B FOREIGN KEY (git_project_id) REFERENCES git_project (id)');
        $this->addSql('ALTER TABLE purchase_offer ADD CONSTRAINT FK_FD1D0414166D1F9C FOREIGN KEY (project_id) REFERENCES git_project (id)');
        $this->addSql('ALTER TABLE purchase_offer ADD CONSTRAINT FK_FD1D0414ED255ED6 FOREIGN KEY (purchaser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trading ADD CONSTRAINT FK_BC19FB58B2A188F1 FOREIGN KEY (sell_offer_id) REFERENCES sell_offer (id)');
        $this->addSql('ALTER TABLE sell_offer ADD CONSTRAINT FK_317ECB62166D1F9C FOREIGN KEY (project_id) REFERENCES git_project (id)');
        $this->addSql('ALTER TABLE sell_offer ADD CONSTRAINT FK_317ECB628DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D74BF81A FOREIGN KEY (project_participation_id) REFERENCES project_participation (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D197CD2A32 FOREIGN KEY (trading_id) REFERENCES trading (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D74BF81A');
        $this->addSql('ALTER TABLE purchase_offer DROP FOREIGN KEY FK_FD1D0414ED255ED6');
        $this->addSql('ALTER TABLE sell_offer DROP FOREIGN KEY FK_317ECB628DE820D9');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('ALTER TABLE project_participation DROP FOREIGN KEY FK_7FC475498820681B');
        $this->addSql('ALTER TABLE purchase_offer DROP FOREIGN KEY FK_FD1D0414166D1F9C');
        $this->addSql('ALTER TABLE sell_offer DROP FOREIGN KEY FK_317ECB62166D1F9C');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D197CD2A32');
        $this->addSql('ALTER TABLE trading DROP FOREIGN KEY FK_BC19FB58B2A188F1');
        $this->addSql('DROP TABLE project_participation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE purchase_offer');
        $this->addSql('DROP TABLE git_project');
        $this->addSql('DROP TABLE trading');
        $this->addSql('DROP TABLE sell_offer');
        $this->addSql('DROP TABLE transaction');
    }
}
