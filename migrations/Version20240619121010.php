<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619121010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("CREATE TABLE `feeds` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NOT NULL DEFAULT '0' COLLATE 'utf8mb4_unicode_ci',
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB");
        $this->addSql("CREATE TABLE `instagram_sources` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            `fan_count` INT(20) NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB");
        $this->addSql("CREATE TABLE `posts` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `url` VARCHAR(150) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB");
        $this->addSql("CREATE TABLE `tiktok_sources` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
            `fan_count` INT(20) NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB");
        $this->addSql("CREATE TABLE `feeds_instagram_connections` (
            `feeds_id` INT(11) NULL DEFAULT NULL,
            `instagram_id` INT(11) NULL DEFAULT NULL,
            INDEX `FK_feeds_instagram_connections_feeds` (`feeds_id`) USING BTREE,
            INDEX `FK_feeds_instagram_connections_instagram_sources` (`instagram_id`) USING BTREE,
            CONSTRAINT `FK_feeds_instagram_connections_feeds` FOREIGN KEY (`feeds_id`) REFERENCES `feeds` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
            CONSTRAINT `FK_feeds_instagram_connections_instagram_sources` FOREIGN KEY (`instagram_id`) REFERENCES `instagram_sources` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB");
        $this->addSql("CREATE TABLE `feeds_posts_connections` (
            `feeds_id` INT(11) NULL DEFAULT NULL,
            `posts_id` INT(11) NULL DEFAULT NULL,
            INDEX `FK_feeds_posts_connections_feeds` (`feeds_id`) USING BTREE,
            INDEX `FK_feeds_posts_connections_posts` (`posts_id`) USING BTREE,
            CONSTRAINT `FK_feeds_posts_connections_feeds` FOREIGN KEY (`feeds_id`) REFERENCES `feeds` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
            CONSTRAINT `FK_feeds_posts_connections_posts` FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB");
        $this->addSql("CREATE TABLE `feeds_tiktok_connections` (
            `feeds_id` INT(11) NULL DEFAULT NULL,
            `tiktok_id` INT(11) NULL DEFAULT NULL,
            INDEX `FK_feeds_tiktok_connections_feeds` (`feeds_id`) USING BTREE,
            INDEX `FK_feeds_tiktok_connections_tiktok_sources` (`tiktok_id`) USING BTREE,
            CONSTRAINT `FK_feeds_tiktok_connections_feeds` FOREIGN KEY (`feeds_id`) REFERENCES `feeds` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
            CONSTRAINT `FK_feeds_tiktok_connections_tiktok_sources` FOREIGN KEY (`tiktok_id`) REFERENCES `tiktok_sources` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION
        )
        COLLATE='utf8mb4_unicode_ci'
        ENGINE=InnoDB");

        $this->addSql("INSERT INTO `feeds` (`id`, `name`) VALUES
        (1, 'John Stephneson'),
        (2, 'July Hamsphert'),
        (3, 'Franz Johanz')");
        $this->addSql("INSERT INTO `posts` (`id`, `url`) VALUES
        (1, 'www.instagram.com/post1'),
        (2, 'www.instagram.com/post2'),
        (3, 'www.instagram.com/post3'),
        (4, 'www.tiktok.com/post1'),
        (5, 'www.tiktok.com/post2'),
        (6, 'www.tiktok.com/post3')");
        $this->addSql("INSERT INTO `instagram_sources` (`id`, `name`, `fan_count`) VALUES
        (1, 'holy_july', 5000000),
        (2, 'howto_steve', 4600000),
        (3, 'agatha_carl', 820000),
        (4, 'my_adventures', 1945000)");
        $this->addSql("INSERT INTO `tiktok_sources` (`id`, `name`, `fan_count`) VALUES
        (1, 'new_wave', 654210),
        (2, 'harry_potter_lovers', 1020000),
        (3, 'onehundred_whys', 2460000),
        (4, 'black_rose', 1875000)");
        $this->addSql("INSERT INTO `feeds_posts_connections` (`feeds_id`, `posts_id`) VALUES
        (1, 1),
        (1, 2),
        (3, 4),
        (2, 6),
        (2, 3),
        (2, 5)");
        $this->addSql("INSERT INTO `feeds_instagram_connections` (`feeds_id`, `instagram_id`) VALUES
        (2, 1),
        (2, 3),
        (3, 2),
        (1, 4)");
        $this->addSql("INSERT INTO `feeds_tiktok_connections` (`feeds_id`, `tiktok_id`) VALUES
        (2, 2),
        (2, 1),
        (3, 3),
        (1, 4)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('');
    }
}
