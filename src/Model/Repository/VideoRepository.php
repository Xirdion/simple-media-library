<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Model\Repository;

use App\Database\Database;
use App\Model\Collection\VideoCollection;
use App\Model\VideoModel;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Session\Session;

class VideoRepository
{
    protected static Database $database;
    protected static VideoCollection $collection;
    /** @var string[] */
    protected static array $fields;

    public static function findAll(Session $session): ?VideoCollection
    {
        $fields = array_filter(self::getFields(), static fn ($field) => 'src' !== $field);
        $query = 'SELECT %s FROM video ORDER BY id';
        $query = sprintf($query, implode(', ', $fields));

        try {
            $result = self::getDatabase()
                ->prepare($query)
                ->execute()
            ;
        } catch (\Exception $e) {
            $session->set('errorMsg', $e->getMessage());

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

        if (false === $result || 0 === $result->num_rows) {
            return null;
        }

        $row = $result->fetch_assoc();
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

    /**
     * @return string[]
     */
    protected static function getFields(): array
    {
        if (false === isset(self::$fields)) {
            $reflect = new ReflectionClass(new static());
            $fields = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);
            self::$fields = array_map(static fn ($field) => $field->getName(), $fields);
        }

        return self::$fields;
    }
}
