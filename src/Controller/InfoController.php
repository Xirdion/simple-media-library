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
        if (false === $this->request->isMethod('get')) {
            return;
        }

        // Try to load the video by request info
        $video = $this->loadVideoById();

        $generator = new XmlGenerator();
        $this->response = $generator->generate($video);

        $download = (int) $this->request->query->get('download');
        if (1 === $download) {
            // Create the file and write to it
            $file = 'video.xml';
            if (false === file_put_contents($file, $this->response)) {
                throw new \Exception('Could not create xml file');
            }

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            header('Content-Type: text/plain');
            readfile($file);

            // Stop processing here to download the file immediately
            exit();
        }

        // Set the header to xml for the transfer of the xml data
        header('Content-type: text/xml');
    }
}
