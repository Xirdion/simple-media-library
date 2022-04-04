<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App;

use App\Controller\ControllerFactory;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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
        $sessionId = $request->cookies->get('PHPSESSID');
        if (false === $request->hasSession()) {
            $session = new Session();
            if ($sessionId) {
                // Set the id of a previous session
                $session->setId($sessionId);
            }
            $session->start();
            $request->setSession($session);
        } else {
            $session = $request->getSession();
        }

        try {
            $controller = ControllerFactory::getController($request);
            $controller->handleRequest();
            echo $controller->getResponse();
            exit();
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            include PROJECT_DIR . 'template/error.html';

            return;
        }
    }
}
