<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class ControllerFactory
{
    public static function getController(Request $request): ControllerInterface
    {
        // Depending on the given route a specific controller is created
        $pathInfo = $request->getPathInfo();
        $controller = match ($pathInfo) {
            '/' => new ListController($request),
            '/video/edit', '/video/new' => new EditController($request),
            '/video/delete' => new DeleteController($request),
            '/video/info' => new InfoController($request),
            '/video/stream' => new VideoController($request),
            default => null,
        };

        // If there is no result throw new error
        if (null === $controller) {
            throw new \Exception('404: Page not found!');
        }

        return $controller;
    }
}
