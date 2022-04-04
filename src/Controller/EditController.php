<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Controller;

use App\Model\VideoModel;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            // Try to load the video instance by request parameter
            $video = $this->loadVideoById();
        }

        $this->renderTemplate(
            PROJECT_DIR . 'template/form.html',
            [
                'formAction' => $this->request->getUri(),
                'video' => $video,
                'mode' => (null === $video->getId() ? 'new' : 'edit'),
                'allowedTypes' => '.' . implode(',.', array_merge(VideoModel::VIDEO_EXTENSIONS, VideoModel::IMAGE_EXTENSIONS)),
                'maxSize' => $this->getMaxFileSize(),
            ],
        );
    }

    private function handleSubmit(): void
    {
        $session = $this->request->getSession();

        $submit = $this->request->request->get('form_submit');
        if ('video_edit' !== $submit) {
            $session->set('errorMsg', 'Something went wrong!');

            return;
        }

        $id = (int) $this->request->request->get('id');
        $title = $this->request->request->get('title');
        $length = (int) $this->request->request->get('length');
        $actors = $this->request->request->get('actors');

        /** @var UploadedFile|null $file */
        $file = $this->request->files->get('src');
        $src = $file?->getContent();
        $fileName = $file?->getClientOriginalName();
        $fileExtension = strtolower((string) $file?->getClientOriginalExtension());
        $fileSize = $file?->getSize();
        $fileMimeType = $file?->getClientMimeType();

        // Check file extension and file size
        if (null !== $src) {
            $extensions = array_merge(VideoModel::VIDEO_EXTENSIONS, VideoModel::IMAGE_EXTENSIONS);
            if (false === \in_array($fileExtension, $extensions, true)) {
                $session->set('errorMsg', sprintf('File with extension %s not allowed!', $fileExtension));

                return;
            }

            // Compare the file size and the max file size in Bytes
            if ($fileSize > ($this->getMaxFileSize() * 1024 * 1024)) {
                $session->set('errorMsg', 'File size is too big!');

                return;
            }
        }

        if (0 === $id) {
            if (false === $session->has('uploadData')) {
                $session->set('uploadData', [1, (new \DateTimeImmutable())->getTimestamp()]);
            } else {
                // Check if there have been 5 uploads within a short amount of time
                [$counter, $time] = (array) $session->get('uploadData');
                if (5 <= (int) $counter) {
                    $session->set('errorMsg', 'Im Moment können Sie keine Dateien mehr hochladen. Bitte Warten Sie einige Zeit.');

                    return;
                }

                $currentTime = new \DateTimeImmutable();
                $diff = $currentTime->getTimestamp() - (int) $time;
                if ($diff < 300) {
                    ++$counter;
                } else {
                    // Reset the count as a long time have been passed
                    $counter = 1;
                }
                $session->set('uploadData', [$counter, $currentTime->getTimestamp()]);
            }

            $video = new VideoModel();
        } else {
            $video = VideoModel::findById($id);
            $session->set('errorMsg', 'Something went wrong!');
            if (null === $video) {
                return;
            }

            // Set the update date
            $video->setUpdatedAt(new \DateTimeImmutable());
        }
        $video->setTitle($title);
        $video->setLength($length);
        $video->setActors($actors);

        // Set src only if it is given
        if (null !== $src) {
            $video->setSrc($src);
            $video->setFileName($fileName);
            $video->setFileExtension($fileExtension);
            $video->setFileSize($fileSize);
            $video->setFileMimeType($fileMimeType);
        }
        $video->save($this->request->getSession());

        header('Location: ' . $this->request->getSchemeAndHttpHost());
    }

    private function getMaxFileSize(): int
    {
        $maxUpload = (int) \ini_get('upload_max_filesize');
        $maxPost = (int) \ini_get('post_max_size');
        $memoryLimit = (int) \ini_get('memory_limit');

        return min($maxUpload, $maxPost, $memoryLimit);
    }
}
