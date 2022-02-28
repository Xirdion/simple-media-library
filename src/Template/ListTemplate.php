<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Template;

use App\Video\Video;

class ListTemplate
{
    /** @var \ArrayIterator<int, Video> */
    private \ArrayIterator $videoList;

    /**
     * @return \ArrayIterator<int, Video>
     */
    public function getVideoList(): \ArrayIterator
    {
        return $this->videoList;
    }

    /**
     * @param \ArrayIterator<int, Video> $videoList
     *
     * @return void
     */
    public function setVideoList(\ArrayIterator $videoList): void
    {
        $this->videoList = $videoList;
    }

    public function showTemplate(): void
    {
        include PROJECT_DIR . 'template/index.html';
    }
}
