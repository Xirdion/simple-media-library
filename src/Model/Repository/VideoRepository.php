<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Model\Repository;

use App\Database\Database;
use App\Model\Collection\VideoCollection;
use App\Model\VideoModel;

class VideoRepository
{
    protected static Database $database;
    protected static VideoCollection $collection;
    /** @var string[] */
    protected static array $fields;

    public static function findAll(): ?VideoCollection
    {
        $query = 'SELECT %s FROM video ORDER BY id';
        $query = sprintf($query, implode(', ', self::getFields()));

        try {
            $result = self::getDatabase()
                ->prepare($query)
                ->execute()
            ;
        } catch (\Exception $e) {
            // TODO: Log the exception
            return null;
        }

        if (false === $result) {
            $rows = [];
        } else {
            $rows = $result->fetch_all(\MYSQLI_ASSOC);
        }

        self::$collection = new VideoCollection();
        self::$collection->createFromDbResult($rows);

        return self::$collection;
    }

    public static function findById(int $id): ?VideoModel
    {
        $query = 'SELECT %s FROM video WHERE id = ?';
        $query = sprintf($query, implode(', ', self::getFields()));

        try {
            $result = self::getDatabase()
                ->prepare($query)
                ->execute([$id])
            ;
        } catch (\Exception $e) {
            // TODO: Log the exception
            return null;
        }

        if (false === $result) {
            $row = [];
        } else {
            $row = $result->fetch_assoc();
        }

        $video = new VideoModel();
        $video->setPropertiesFromArray($row);

        return $video;
    }

    protected static function getDatabase(): Database
    {
        if (false === isset(self::$database)) {
            self::$database = Database::getInstance();
        }

        return self::$database;
    }

    protected static function getFields(): array
    {
        if (false === isset(self::$fields)) {
            self::$fields = ['id', 'name'];
        }

        return self::$fields;
    }
}
