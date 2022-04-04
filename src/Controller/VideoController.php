<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Controller;

class VideoController extends AbstractController
{
    public function handleRequest(): void
    {
        $video = $this->loadVideoById();
        if (null === $video) {
            $session = $this->request->getSession();
            $session->set('errorMsg', 'Something went wrong');

            return;
        }

        // Prepare the headers to stream the video
        header('Content-Disposition: attachment; filename=' . $video->getFileName());
        header('Content-length: ' . $video->getFileSize());
        header('Content-type: ' . $video->getFileMimeType());

        // Transfer the video content
        $this->response = $video->getSrc();
    }
}
