<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
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
                $controller = new EditController($request);
                break;
            case '/video/delete':
                break;
            case '/video/info':
                break;
        }

        if (null === $controller) {
            throw new \Exception('404: Page not found!');
        }

        return $controller;
    }
}
