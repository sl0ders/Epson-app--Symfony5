<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211006080347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cartridge (id INT AUTO_INCREMENT NOT NULL, printer_id INT NOT NULL, name VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, serial_number VARCHAR(255) DEFAULT NULL, color VARCHAR(255) NOT NULL, print_average BIGINT DEFAULT NULL, rest_days DOUBLE PRECISION DEFAULT NULL, rest_prints DOUBLE PRECISION DEFAULT NULL, use_by_day DOUBLE PRECISION DEFAULT NULL, rest_print_bw DOUBLE PRECISION DEFAULT NULL, rest_print_color DOUBLE PRECISION DEFAULT NULL, rest_ink_percent INT DEFAULT NULL, color_code VARCHAR(255) NOT NULL, INDEX IDX_E964875246EC494A (printer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, fax VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, social_reason VARCHAR(255) DEFAULT NULL, siren VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, ink_breaking_up_lvl INT NOT NULL, ink_breaking_up_days INT NOT NULL, bac_breaking_up_lvl INT NOT NULL, bac_breaking_up_days INT NOT NULL, logo_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, code VARCHAR(255) NOT NULL, zip_code INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, street_number BIGINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consumable (id INT AUTO_INCREMENT NOT NULL, print_id INT NOT NULL, yellow BIGINT NOT NULL, magenta BIGINT NOT NULL, cyan BIGINT NOT NULL, black BIGINT NOT NULL, a3_m BIGINT DEFAULT NULL, a3_c BIGINT DEFAULT NULL, a4_m BIGINT DEFAULT NULL, a4_c BIGINT DEFAULT NULL, ppt BIGINT NOT NULL, mpp BIGINT NOT NULL, cpp BIGINT NOT NULL, a3_dm BIGINT DEFAULT NULL, a4_dm BIGINT DEFAULT NULL, a3_dc BIGINT DEFAULT NULL, a4_dc BIGINT DEFAULT NULL, date_update DATETIME NOT NULL, mbr BIGINT DEFAULT NULL, INDEX IDX_4475F095C62133AC (print_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consumable_delta (id INT AUTO_INCREMENT NOT NULL, consumable_id INT DEFAULT NULL, printer_id INT DEFAULT NULL, ppt_delta BIGINT DEFAULT NULL, mpp_delta BIGINT DEFAULT NULL, cpp_delta BIGINT DEFAULT NULL, a3_m_delta BIGINT DEFAULT NULL, a3_c_delta BIGINT DEFAULT NULL, a4_m_delta BIGINT DEFAULT NULL, a4_c_delta BIGINT DEFAULT NULL, yellow_delta BIGINT NOT NULL, magenta_delta BIGINT NOT NULL, black_delta BIGINT NOT NULL, cyan_delta BIGINT NOT NULL, update_at DATETIME NOT NULL, mbr_delta BIGINT DEFAULT NULL, a4_dm_delta BIGINT DEFAULT NULL, a4_dc_delta BIGINT DEFAULT NULL, a3_dc_delta BIGINT DEFAULT NULL, a3_dm_delta BIGINT DEFAULT NULL, INDEX IDX_3296B05AA94ADB61 (consumable_id), INDEX IDX_3296B05A46EC494A (printer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_report (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, report_id INT DEFAULT NULL, created_at DATETIME NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_F308EA8BF624B39D (sender_id), INDEX IDX_F308EA8B4BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, created_at DATETIME NOT NULL, message LONGTEXT NOT NULL, is_enabled TINYINT(1) NOT NULL, readAt DATETIME DEFAULT NULL, expirationDate DATETIME DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, id_path INT DEFAULT NULL, INDEX IDX_BF5476CAF624B39D (sender_id), INDEX IDX_BF5476CACD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_cartridge (id INT AUTO_INCREMENT NOT NULL, cartridge_id INT NOT NULL, user_id INT NOT NULL, client_id INT DEFAULT NULL, printer_id INT DEFAULT NULL, note LONGTEXT DEFAULT NULL, order_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, state VARCHAR(60) NOT NULL, order_code VARCHAR(255) NOT NULL, quantity INT NOT NULL, INDEX IDX_55E3FCAA376494CA (cartridge_id), INDEX IDX_55E3FCAAA76ED395 (user_id), INDEX IDX_55E3FCAA19EB6921 (client_id), INDEX IDX_55E3FCAA46EC494A (printer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE printer (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, office VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, mac VARCHAR(255) DEFAULT NULL, sn VARCHAR(255) DEFAULT NULL, swv VARCHAR(255) DEFAULT NULL, des VARCHAR(255) DEFAULT NULL, update_at DATETIME NOT NULL, enable TINYINT(1) NOT NULL, state VARCHAR(60) NOT NULL, subname VARCHAR(255) DEFAULT NULL, INDEX IDX_8D4C79ED979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recovery_bac (id INT AUTO_INCREMENT NOT NULL, printer_id INT NOT NULL, use_by_day DOUBLE PRECISION DEFAULT NULL, rest_days DOUBLE PRECISION DEFAULT NULL, rest_prints DOUBLE PRECISION DEFAULT NULL, rest_bac_percent DOUBLE PRECISION DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, print_average INT DEFAULT NULL, UNIQUE INDEX UNIQ_5AD4ADE746EC494A (printer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, printer_id INT DEFAULT NULL, ink_id INT DEFAULT NULL, order_cartridge_id INT DEFAULT NULL, report_code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, subject VARCHAR(255) DEFAULT NULL, statut TINYINT(1) NOT NULL, INDEX IDX_C42F7784979B1AD6 (company_id), INDEX IDX_C42F778446EC494A (printer_id), INDEX IDX_C42F7784CD1C5A3D (ink_id), INDEX IDX_C42F778433A400A0 (order_cartridge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_email_recipient TINYINT(1) NOT NULL, firstname VARCHAR(30) DEFAULT NULL, lastname VARCHAR(30) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, zip_code INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, street_number BIGINT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cartridge ADD CONSTRAINT FK_E964875246EC494A FOREIGN KEY (printer_id) REFERENCES printer (id)');
        $this->addSql('ALTER TABLE consumable ADD CONSTRAINT FK_4475F095C62133AC FOREIGN KEY (print_id) REFERENCES printer (id)');
        $this->addSql('ALTER TABLE consumable_delta ADD CONSTRAINT FK_3296B05AA94ADB61 FOREIGN KEY (consumable_id) REFERENCES consumable (id)');
        $this->addSql('ALTER TABLE consumable_delta ADD CONSTRAINT FK_3296B05A46EC494A FOREIGN KEY (printer_id) REFERENCES printer (id)');
        $this->addSql('ALTER TABLE message_report ADD CONSTRAINT FK_F308EA8BF624B39D FOREIGN KEY (sender_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE message_report ADD CONSTRAINT FK_F308EA8B4BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF624B39D FOREIGN KEY (sender_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE order_cartridge ADD CONSTRAINT FK_55E3FCAA376494CA FOREIGN KEY (cartridge_id) REFERENCES cartridge (id)');
        $this->addSql('ALTER TABLE order_cartridge ADD CONSTRAINT FK_55E3FCAAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_cartridge ADD CONSTRAINT FK_55E3FCAA19EB6921 FOREIGN KEY (client_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE order_cartridge ADD CONSTRAINT FK_55E3FCAA46EC494A FOREIGN KEY (printer_id) REFERENCES printer (id)');
        $this->addSql('ALTER TABLE printer ADD CONSTRAINT FK_8D4C79ED979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE recovery_bac ADD CONSTRAINT FK_5AD4ADE746EC494A FOREIGN KEY (printer_id) REFERENCES printer (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778446EC494A FOREIGN KEY (printer_id) REFERENCES printer (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784CD1C5A3D FOREIGN KEY (ink_id) REFERENCES cartridge (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778433A400A0 FOREIGN KEY (order_cartridge_id) REFERENCES order_cartridge (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_cartridge DROP FOREIGN KEY FK_55E3FCAA376494CA');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784CD1C5A3D');
        $this->addSql('ALTER TABLE message_report DROP FOREIGN KEY FK_F308EA8BF624B39D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF624B39D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CACD53EDB6');
        $this->addSql('ALTER TABLE order_cartridge DROP FOREIGN KEY FK_55E3FCAA19EB6921');
        $this->addSql('ALTER TABLE printer DROP FOREIGN KEY FK_8D4C79ED979B1AD6');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784979B1AD6');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE consumable_delta DROP FOREIGN KEY FK_3296B05AA94ADB61');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778433A400A0');
        $this->addSql('ALTER TABLE cartridge DROP FOREIGN KEY FK_E964875246EC494A');
        $this->addSql('ALTER TABLE consumable DROP FOREIGN KEY FK_4475F095C62133AC');
        $this->addSql('ALTER TABLE consumable_delta DROP FOREIGN KEY FK_3296B05A46EC494A');
        $this->addSql('ALTER TABLE order_cartridge DROP FOREIGN KEY FK_55E3FCAA46EC494A');
        $this->addSql('ALTER TABLE recovery_bac DROP FOREIGN KEY FK_5AD4ADE746EC494A');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778446EC494A');
        $this->addSql('ALTER TABLE message_report DROP FOREIGN KEY FK_F308EA8B4BD2A4C0');
        $this->addSql('ALTER TABLE order_cartridge DROP FOREIGN KEY FK_55E3FCAAA76ED395');
        $this->addSql('DROP TABLE cartridge');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE consumable');
        $this->addSql('DROP TABLE consumable_delta');
        $this->addSql('DROP TABLE message_report');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE order_cartridge');
        $this->addSql('DROP TABLE printer');
        $this->addSql('DROP TABLE recovery_bac');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE `user`');
    }
}
