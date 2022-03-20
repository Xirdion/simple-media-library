<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App;

use App\Controller\ControllerFactory;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\Request;

class App
{
    public function init(): void
    {
        // Load the environment variables
        $dotEnv = new Dotenv();
        $filePath = Path::join(PROJECT_DIR, '.env');
        $dotEnv->bootEnv($filePath, 'prod');

        // Get a request object from PHP super globals
        $request = Request::createFromGlobals();

        try {
            $controller = ControllerFactory::getController($request);
            $controller->handleRequest();
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            include PROJECT_DIR . 'template/error.html';

            return;
        }
    }
}
