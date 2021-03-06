<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Controller;

use App\Model\Collection\VideoCollection;
use App\Model\VideoModel;

class ListController extends AbstractController
{
    public function handleRequest(): void
    {
        $videos = VideoModel::findAll($this->request->getSession());
        if (null === $videos) {
            $videoList = (new VideoCollection())->getIterator();
        } else {
            $videoList = $videos->getIterator();
        }

        $this->renderTemplate(
            PROJECT_DIR . 'template/index.html',
            ['videos' => $videoList],
        );
    }
}
