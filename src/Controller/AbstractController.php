<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Controller;

use App\Model\VideoModel;
use App\Template\Template;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController implements ControllerInterface
{
    public function __construct(
        protected Request $request,
        protected string $response = '',
    ) {
    }

    /**
     * @throws RuntimeException
     *
     * @return VideoModel
     */
    public function loadVideoById(): VideoModel
    {
        // Try to get the video ID from GET or POST parameter
        $id = (int) $this->request->get('id');
        if (!$id) {
            throw new \RuntimeException('Missing video ID!');
        }

        $video = VideoModel::findById($id);
        if (null === $video) {
            throw new \RuntimeException('Video not found!');
        }

        return $video;
    }

    /**
     * @param string $file
     * @param array  $data
     *
     * @return void
     */
    public function renderTemplate(string $file, array $data): void
    {
        ob_start();
        $template = new Template($file);
        $template->setData($data);
        $template->render();

        $this->response = ob_get_clean();
    }

    public function getResponse(): string
    {
        return $this->response;
    }
}
