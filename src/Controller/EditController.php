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
                'allowedTypes' => '.' . implode(',.', array_merge($video::VIDEO_EXTENSIONS, $video::IMAGE_EXTENSIONS)),
                'maxSize' => $this->getMaxFileSize(),
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
                throw new \Exception(sprintf('File with extension %s not allowed!', $fileExtension));
            }

            // Compare the file size and the max file size in Bytes
            if ($fileSize > ($this->getMaxFileSize() * 1024 * 1024)) {
                throw new \Exception('File size is too big!');
            }
        }

        if (0 === $id) {
            $video = new VideoModel();
        } else {
            $video = VideoModel::findById($id);
            if (null === $video) {
                throw new \Exception('Something went wrong!');
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
        $video->save();

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
