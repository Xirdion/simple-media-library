<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Database;

class Database
{
    private static Database $database;
    private \mysqli $link;

    private function __construct()
    {
        $host = $_SERVER['DB_HOST'];
        $user = $_SERVER['DB_USER'];
        $password = $_SERVER['DB_PASSWORD'];
        $name = $_SERVER['DB_NAME'];

        // Try to connect to database
        $link = mysqli_connect($host, $user, $password);
        if (false === $link) {
            throw new \Exception('Something went wrong!');
        }

        // Set the class variable
        $this->link = $link;

        // Select the given database
        mysqli_select_db($this->link, $name);
    }

    public static function getInstance(): self
    {
        if (false === isset(self::$database)) {
            self::$database = new self();
        }

        return self::$database;
    }

    /**
     * @return array<string>
     */
    public function getVideoList(): array
    {
        $result = mysqli_query($this->link, 'SELECT * FROM video;');
        if (false === $result) {
            // throw new \Exception('Something went wrong!');
            return [];
        }

        return $result->fetch_all(\MYSQLI_ASSOC);
    }
}
