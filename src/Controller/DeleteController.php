<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Controller;

class DeleteController extends AbstractController
{
    public function handleRequest(): void
    {
        if (false === $this->request->isMethod('post')) {
            throw new \Exception('You cant access the delete route directly!');
        }

        $video = $this->loadVideoById();
        if (null === $video) {
            throw new \Exception('Something went wrong!');
        }

        $submit = $this->request->request->get('form_submit');
        if ('video_edit' !== $submit) {
            return;
        }

        $video->delete();
        unset($video);

        header('Location: ' . $this->request->getSchemeAndHttpHost());
    }
}
