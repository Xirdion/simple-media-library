<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Controller;

class DeleteController extends AbstractController
{
    public function handleRequest(): void
    {
        if (false === $this->request->isMethod('post')) {
            throw new \Exception('You cant access the delete route directly!');
        }

        // Try to load a video instance
        $video = $this->loadVideoById();

        $submit = $this->request->request->get('form_submit');
        if ('video_delete' !== $submit) {
            return;
        }

        $video->delete();
        unset($video);

        header('Location: ' . $this->request->getSchemeAndHttpHost());
    }
}
