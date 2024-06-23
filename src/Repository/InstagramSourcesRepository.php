<?php

namespace App\Repository;

use App\Entity\InstagramSources;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Instagram>
 */
class InstagramSourcesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstagramSources::class);
    }

    public function findIt($value = null, $connection = null): array
    {
        $answer = [];
        if (!is_null($value)) {
            $connection = (is_null($connection)) ? 'prod' : $connection;
            $conn = $this->getEntityManager()->getConnection($connection);
            $sql = '
            SELECT  ´f´.*,
                    GROUP_CONCAT(DISTINCT ´iso´.name) AS instagramName,
                    GROUP_CONCAT(DISTINCT ´iso´.fan_count) AS instagramFan,
                    GROUP_CONCAT(DISTINCT ´tso´.name) AS tiktokName,
                    GROUP_CONCAT(DISTINCT ´tso´.fan_count) AS tiktokFan
            FROM feeds AS ´f´
            LEFT JOIN feeds_instagram_connections AS ´fic´ ON ´fic´.feeds_id = ´f´.id
            LEFT JOIN feeds_tiktok_connections AS ´ftc´ ON ´f´.id = ´ftc´.feeds_id
            LEFT JOIN instagram_sources AS ´iso´ ON ´iso´.id = ´fic´.instagram_id
            LEFT JOIN tiktok_sources AS ´tso´ ON ´tso´.id = ´ftc´.tiktok_id
            WHERE ´f´.id = :id';
            $resultSet = $conn->executeQuery($sql, ['id' => $value]);
            $answer = $resultSet->fetchAllAssociative();
        }
        return $answer;
    }

    public function findItOnlyIn($value = null, $source = null, $connection = null): array
    {
        $answer = [];
        if (!is_null($value)) {
            $connection = (is_null($connection)) ? 'prod' : $connection;
            $conn = $this->getEntityManager()->getConnection($connection);
            $subQuery = [];
            if(is_null($source) or $source === 'instagram'){
                $subQuery[] = "GROUP_CONCAT(DISTINCT ´iso´.name) AS media,
                GROUP_CONCAT(DISTINCT ´iso´.fan_count) AS fan";
                $subQuery[] = "LEFT JOIN feeds_instagram_connections AS ´fic´ ON ´fic´.feeds_id = ´f´.id
                LEFT JOIN instagram_sources AS ´iso´ ON ´iso´.id = ´fic´.instagram_id";
            } else {
                $subQuery[] = "GROUP_CONCAT(DISTINCT ´tso´.name) AS media,
                GROUP_CONCAT(DISTINCT ´tso´.fan_count) AS fan";
                $subQuery[] = "LEFT JOIN feeds_tiktok_connections AS ´ftc´ ON ´f´.id = ´ftc´.feeds_id
                LEFT JOIN tiktok_sources AS ´tso´ ON ´tso´.id = ´ftc´.tiktok_id";
            }
            $sql = "SELECT  ´f´.*,
                    {$subQuery[0]}
                    FROM feeds AS ´f´
                    {$subQuery[1]}
                    WHERE ´f´.id = :id";
            $resultSet = $conn->executeQuery($sql, ['id' => $value]);
            $answer = $resultSet->fetchAllAssociative();
        }
        return $answer;
    }

    public function findItOnlyInWithPosts($value = null, $source = null, $numOfPosts = null, $connection = null): array
    {
        $answer = [];
        if (!is_null($value)) {
            $connection = (is_null($connection)) ? 'prod' : $connection;
            $conn = $this->getEntityManager()->getConnection($connection);
            $subQuery = [];
            if(is_null($source) or $source === 'instagram'){
                $subQuery[] = "GROUP_CONCAT(DISTINCT ´iso´.name) AS media,
                GROUP_CONCAT(DISTINCT ´iso´.fan_count) AS fan";
                $subQuery[] = "LEFT JOIN feeds_instagram_connections AS ´fic´ ON ´fic´.feeds_id = ´f´.id
                LEFT JOIN instagram_sources AS ´iso´ ON ´iso´.id = ´fic´.instagram_id";
            } else {
                $subQuery[] = "GROUP_CONCAT(DISTINCT ´tso´.name) AS media,
                GROUP_CONCAT(DISTINCT ´tso´.fan_count) AS fan";
                $subQuery[] = "LEFT JOIN feeds_tiktok_connections AS ´ftc´ ON ´f´.id = ´ftc´.feeds_id
                LEFT JOIN tiktok_sources AS ´tso´ ON ´tso´.id = ´ftc´.tiktok_id";
            }
            $sql = "SELECT  ´f´.*,
                    {$subQuery[0]},
                    SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT ´p´.url), ',', :posts) AS postUrl
                    FROM feeds AS ´f´
                    {$subQuery[1]}
                    LEFT JOIN feeds_posts_connections AS ´fpc´ ON ´fpc´.feeds_id = ´f´.id
                    LEFT JOIN posts AS ´p´ ON ´p´.id = ´fpc´.posts_id
                    WHERE ´f´.id = :id";
            $resultSet = $conn->executeQuery($sql, ['id' => $value, 'posts' => $numOfPosts]);
            $answer = $resultSet->fetchAllAssociative();
        }
        return $answer;
    }

    public function findItOnlyWithPosts($value = null, $numOfPosts = null, $connection = null): array
    {
        $answer = [];
        if (!is_null($value)) {
            $connection = (is_null($connection)) ? 'prod' : $connection;
            $conn = $this->getEntityManager()->getConnection($connection);
            $sql = "SELECT  ´f´.*,
                            SUBSTRING_INDEX(GROUP_CONCAT(DISTINCT ´p´.url), ',', :posts) AS postUrl
                    FROM feeds AS ´f´
                    LEFT JOIN feeds_posts_connections AS ´fpc´ ON ´fpc´.feeds_id = ´f´.id
                    LEFT JOIN posts AS ´p´ ON ´p´.id = ´fpc´.posts_id
                    WHERE ´f´.id = :id";
            $resultSet = $conn->executeQuery($sql, ['id' => $value, 'posts' => $numOfPosts]);
            $answer = $resultSet->fetchAllAssociative();
        }
        return $answer;
    }
}
