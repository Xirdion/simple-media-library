<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

use App\App;

require __DIR__ . '/../vendor/autoload.php';

const PROJECT_DIR = __DIR__ . '/../';

$app = new App();
$app->init();
