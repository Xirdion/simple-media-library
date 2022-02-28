<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Video;

/**
 * @implements \IteratorAggregate<int, Video>
 */
class VideoList implements \IteratorAggregate
{
    /** @var array<int, Video> */
    private array $videos;

    public function __construct()
    {
        // Init the video list
        $this->videos = [];
    }

    /**
     * @return \ArrayIterator<int, Video>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->videos);
    }
}
