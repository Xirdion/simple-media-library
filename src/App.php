<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App;

use App\Video\Video;
use App\Video\VideoList;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Path;

class App
{
    /** @var \ArrayIterator<int, Video> */
    private \ArrayIterator $videoList;

    public function init(): void
    {
        // Load the environment variables
        $dotEnv = new Dotenv();
        $filePath = Path::join(PROJECT_DIR, '.env');
        $dotEnv->bootEnv($filePath, 'prod');

        $videoList = new VideoList();
        $this->videoList = $videoList->getIterator();

        $this->handleRoute();
    }

    private function handleRoute(): void
    {
        $this->showList();
    }

    private function showList(): void
    {
        $video = new Video();
        $video->setId(1);
        $video->setName('test-video');

        // Add a video to the list
        try {
            $this->videoList->append($video);
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        include PROJECT_DIR . 'template/index.html';
    }
}
