<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

use App\App;

require __DIR__ . '/../vendor/autoload.php';

const PROJECT_DIR = __DIR__ . '/../';

$app = new App();
$app->init();
