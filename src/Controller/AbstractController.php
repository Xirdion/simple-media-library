<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController implements ControllerInterface
{
    public function __construct(
        protected Request $request,
    ) {
    }
}
