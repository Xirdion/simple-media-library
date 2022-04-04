<?php

declare(strict_types=1);

/*
 * @author    https://github.com/Xirdion
 * @link      https://github.com/Xirdion/simple-media-library
 */

namespace App\Model;

use App\Model\Repository\VideoRepository;
use DateTimeImmutable;
use DateTimeInterface;

class VideoModel extends VideoRepository
{
    public const VIDEO_EXTENSIONS = ['mp4', 'webm', 'ogv', 'f4v'];
    public const IMAGE_EXTENSIONS = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

    private int $id;
    private string $title;
    private int $length = 0;
    private ?string $actors;
    private ?string $src;
    private ?string $fileName;
    private ?string $fileExtension;
    private int $fileSize;
    private ?string $fileMimeType;
    private DateTimeInterface $created_at;
    private DateTimeInterface $updated_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title ?? null;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length ?? null;
    }

    /**
     * @param int $length
     */
    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    /**
     * @return string|null
     */
    public function getActors(): ?string
    {
        return $this->actors ?? '';
    }

    /**
     * @param string|null $actors
     */
    public function setActors(?string $actors): void
    {
        $this->actors = $actors;
    }

    /**
     * @return string|null
     */
    public function getSrc(): ?string
    {
        return $this->src ?? '';
    }

    /**
     * @param string|null $src
     */
    public function setSrc(?string $src): void
    {
        $this->src = $src;
    }

    /**
     * @return bool
     */
    public function hasSrc(): bool
    {
        return null !== ($this->src ?? null);
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName ?? '';
    }

    /**
     * @param string|null $fileName
     */
    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileExtension(): string
    {
        return $this->fileExtension ?? '';
    }

    /**
     * @param string|null $fileExtension
     */
    public function setFileExtension(?string $fileExtension): void
    {
        $this->fileExtension = $fileExtension;
    }

    /**
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * @param int|null $fileSize
     */
    public function setFileSize(?int $fileSize): void
    {
        $this->fileSize = (int) $fileSize;
    }

    /**
     * @return string
     */
    public function getFileMimeType(): string
    {
        return $this->fileMimeType ?? '';
    }

    /**
     * @param string|null $fileMimeType
     */
    public function setFileMimeType(?string $fileMimeType): void
    {
        $this->fileMimeType = $fileMimeType;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    /**
     * @param DateTimeInterface $created_at
     */
    public function setCreatedAt(DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return bool
     */
    public function isVideo(): bool
    {
        if (null === $this->fileExtension) {
            return false;
        }

        return \in_array($this->fileExtension, self::VIDEO_EXTENSIONS, true);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at->format('Y-m-d H:i:s');
    }

    /**
     * @param DateTimeInterface $updated_at
     */
    public function setUpdatedAt(DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function setPropertiesFromArray(array $data): void
    {
        foreach ($data as $field => $value) {
            $value = match ($field) {
                'created_at', 'updated_at' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value),
                default => $value,
            };
            $this->{$field} = $value;
        }
    }

    public function save(): void
    {
        $fields = array_filter(static::getFields(), static fn (string $field) => 'id' !== $field);
        $methodNames = array_map(static fn (string $field) => implode('', array_map(static fn (string $fieldPart) => ucfirst($fieldPart), explode('_', $field))), $fields);
        $values = array_map(fn (string $method) => $this->{'get' . $method}(), $methodNames);

        // Switch between UPDATE AND INSERT if video.id is set or not
        if (null === $this->getId()) {
            $columns = implode(',', $fields);
            $query = 'INSERT INTO video (' . $columns . ') VALUES (' . substr(str_repeat('?,', \count($fields)), 0, -1) . ')';
        } else {
            $columns = implode(', ', array_map(static fn (string $field) => $field . ' = ?', $fields));
            $query = 'UPDATE video SET ' . $columns . ' WHERE id = ?';
            $values[] = $this->getId();
        }

        try {
            static::getDatabase()
                ->prepare($query)
                ->execute($values)
            ;
        } catch (\Exception $e) {
            // TODO: Log the exception
            return;
        }
    }

    public function delete(): void
    {
        $delete = 'DELETE FROM video WHERE id = ?';
        try {
            static::getDatabase()
                ->prepare($delete)
                ->execute([$this->getId()])
            ;
        } catch (\Exception $e) {
            // TODO: Log the exception
            return;
        }
    }
}
