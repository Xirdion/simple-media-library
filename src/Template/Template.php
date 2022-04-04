<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Template;

use Symfony\Component\HttpFoundation\Session\Session;

class Template
{
    private array $data;
    private string $errorMsg = '';

    public function __construct(
        private string $template,
        private Session $session,
    ) {
    }

    public function __get($key): mixed
    {
        if (false === isset($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }

    public function __set($key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function __isset(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function render(): void
    {
        if (true === $this->session->has('errorMsg')) {
            $this->errorMsg = $this->session->get('errorMsg');
            $this->session->remove('errorMsg');
        }

        if (file_exists($this->template)) {
            include $this->template;
        }
    }
}
