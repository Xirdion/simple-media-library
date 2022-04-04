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
        $session = $this->request->getSession();
        if (false === $this->request->isMethod('post')) {
            $session->set('errorMsg', 'You cant access the delete route directly!');

            return;
        }

        // Try to load a video instance
        $video = $this->loadVideoById();
        if (null === $video) {
            $session->set('errorMsg', 'Something went wrong!');

            return;
        }

        $submit = $this->request->request->get('form_submit');
        if ('video_delete' !== $submit) {
            return;
        }

        $video->delete($this->request->getSession());
        unset($video);

        header('Location: ' . $this->request->getSchemeAndHttpHost());
    }
}
