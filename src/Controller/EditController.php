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
        $id = $this->request->query->get('id');
        if (null === $id) {
            throw new \Exception('Missing video ID!');
        }

        $id = (int) $id;
        if (!$id) {
            throw new \Exception('Missing video ID!');
        }

        $video = VideoModel::findById($id);
        // TODO: Implement handleRequest() method.
    }

    public function renderTemplate(): void
    {
        // TODO: Implement renderTemplate() method.
    }
}
