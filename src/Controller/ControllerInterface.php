<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Controller;

interface ControllerInterface
{
    public function handleRequest(): void;

    /**
     * @param string              $file
     * @param array<mixed, mixed> $data
     *
     * @return void
     */
    public function renderTemplate(string $file, array $data): void;

    public function getResponse(): string;
}
