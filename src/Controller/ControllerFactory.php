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
        $controller = null;
        $pathInfo = $request->getPathInfo();
        switch ($pathInfo) {
            case '/':
                $controller = new ListController($request);
                break;
            case '/video/edit':
            case '/video/new':
                $controller = new EditController($request);
                break;
            case '/video/delete':
                $controller = new DeleteController($request);
                break;
            case '/video/info':
                $controller = new InfoController($request);
                break;
            case '/video/stream':
                $controller = new VideoController($request);
                break;
        }

        if (null === $controller) {
            throw new \Exception('404: Page not found!');
        }

        return $controller;
    }
}
