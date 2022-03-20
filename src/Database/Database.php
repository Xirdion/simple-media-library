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
    private \mysqli $mysqli;
    private \mysqli_stmt $stmt;
    private string $query;

    private function __construct()
    {
        $host = $_SERVER['DB_HOST'];
        $user = $_SERVER['DB_USER'];
        $password = $_SERVER['DB_PASSWORD'];
        $name = $_SERVER['DB_NAME'];

        // Try to connect to database
        mysqli_report(\MYSQLI_REPORT_ERROR | \MYSQLI_REPORT_STRICT);
        $this->mysqli = new \mysqli($host, $user, $password, $name);
    }

    public static function getInstance(): self
    {
        if (false === isset(self::$database)) {
            self::$database = new self();
        }

        return self::$database;
    }

    /**
     * @param string $query
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function prepare(string $query): self
    {
        $query = trim($query);

        if ('' === $query) {
            throw new \Exception('Empty query string!');
        }

        $query = mysqli_real_escape_string($this->mysqli, $query);
        $this->stmt = $this->mysqli->prepare($query);
        $this->query = $query;

        return $this;
    }

    /**
     * @param array|null $params
     *
     * @throws \Exception
     *
     * @return array
     */
    public function execute(?array $params = null): false|\mysqli_result
    {
        if ('' === $this->query) {
            throw new \Exception('Empty query string!');
        }

        if (null === $params) {
            $params = [];
        }

        if (0 !== \count($params)) {
            $types = '';
            foreach ($params as $param) {
                // bool as integer
                if (\is_int($param) || \is_bool($param)) {
                    $types .= 'i';
                } elseif (\is_float($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }

            $this->stmt->bind_param($types, ...$params);
        }

        $this->stmt->execute();

        return $this->stmt->get_result();
    }
}
