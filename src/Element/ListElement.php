<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Element;

use App\Database\Database;

class ListElement
{
    private Database $database;

    public function init(): void
    {
        $this->database = Database::getInstance();
    }

    public function parse(): void
    {
        $videos = $this->database->getVideoList();
    }
}
