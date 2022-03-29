<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Controller;

use App\Model\VideoModel;
use App\Template\Template;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController implements ControllerInterface
{
    public function __construct(
        protected Request $request,
    ) {
    }

    public function loadVideoById(): ?VideoModel
    {
        // Try to get the video ID from GET or POST parameter
        $id = (int) $this->request->get('id');
        if (!$id) {
            throw new \Exception('Missing video ID!');
        }

        return VideoModel::findById($id);
    }

    /**
     * @param string $file
     * @param array  $data
     *
     * @return void
     */
    public function renderTemplate(string $file, array $data): void
    {
        $template = new Template($file);
        $template->setData($data);
        $template->render();
    }
}
