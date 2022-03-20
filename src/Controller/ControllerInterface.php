<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Controller;

interface ControllerInterface
{
    public function handleRequest(): void;

    public function renderTemplate(): void;
}
