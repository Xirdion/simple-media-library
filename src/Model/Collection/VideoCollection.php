<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Model\Collection;

use App\Model\VideoModel;

class VideoCollection implements \IteratorAggregate
{
    /** @var array<int, VideoModel> */
    private array $videos;

    public function __construct()
    {
        // Init the video list
        $this->videos = [];
    }

    /**
     * @return \ArrayIterator<int, VideoModel>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->videos);
    }

    public function createFromDbResult(array $records): void
    {
        foreach ($records as $record) {
            $video = new VideoModel();
            $video->setPropertiesFromArray($record);
            $this->videos[$video->getId()] = $video;
        }
    }
}
