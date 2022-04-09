<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Controller;

use App\Video\XmlGenerator;

class InfoController extends AbstractController
{
    public function handleRequest(): void
    {
        $session = $this->request->getSession();
        if (false === $this->request->isMethod('get')) {
            $session->set('errorMsg', 'Something went wrong!');

            return;
        }

        // Try to load the video by request info
        $video = $this->loadVideoById();
        if (null === $video) {
            $session->set('errorMsg', 'Something went wrong!');

            return;
        }

        $generator = new XmlGenerator();
        $this->response = $generator->generate($video);

        $download = (int) $this->request->query->get('download');
        if (1 === $download) {
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=simple_media_library.xml');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Type: text/plain');
            echo $this->response;

            // Stop processing here to download the file immediately
            exit();
        }

        // Set the header to xml for the transfer of the xml data
        header('Content-type: text/xml');
    }
}
