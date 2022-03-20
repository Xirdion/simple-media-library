<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Controller;

use App\Model\Collection\VideoCollection;
use App\Model\VideoModel;

class ListController extends AbstractController
{
    /** @var \ArrayIterator<int, VideoModel> */
    private \ArrayIterator $videoList;

    public function handleRequest(): void
    {
        $videos = VideoModel::findAll();
        if (null === $videos) {
            $this->videoList = (new VideoCollection())->getIterator();
        } else {
            $this->videoList = $videos->getIterator();
        }

        $this->renderTemplate();
    }

    public function renderTemplate(): void
    {
        include PROJECT_DIR . 'template/index.html';
    }
}
