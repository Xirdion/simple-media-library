<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Controller;

use App\Model\VideoModel;

class EditController extends AbstractController
{
    public function handleRequest(): void
    {
        if ($this->request->isMethod('post')) {
            // Handle form submit
            $this->handleSubmit();

            return;
        }

        // Check if a new model should be created or an existing one updated
        if (null === $this->request->query->get('id')) {
            $video = new VideoModel();
        } else {
            $video = $this->loadVideoById();
            if (null === $video) {
                throw new \Exception('Something went wrong!');
            }
        }

        $this->renderTemplate(
            PROJECT_DIR . 'template/form.html',
            [
                'formAction' => $this->request->getUri(),
                'video' => $video,
            ],
        );
    }

    private function handleSubmit(): void
    {
        $submit = $this->request->request->get('form_submit');
        if ('video_edit' !== $submit) {
            return;
        }

        $id = (int) $this->request->request->get('id');
        $title = $this->request->request->get('title');
        $length = (int) $this->request->request->get('length');
        $actors = $this->request->request->get('actors');

        if (0 === $id) {
            $video = new VideoModel();
        } else {
            $video = VideoModel::findById($id);
            if (null === $video) {
                throw new \Exception('Something went wrong!');
            }
        }
        $video->setTitle($title);
        $video->setLength($length);
        $video->setActors($actors);
        $video->save();

        header('Location: ' . $this->request->getSchemeAndHttpHost());
    }
}
