<?php

declare(strict_types=1);

/*
 * @author     https://github.com/Xirdion
 * @link       https://github.com/sowieso-web/contao-basic
 */

namespace App\Model;

use App\Model\Repository\VideoRepository;

class VideoModel extends VideoRepository
{
    protected int $id;
    protected string $name;
    protected string $title;
    protected float $length;

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
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return float|null
     */
    public function getLength(): ?float
    {
        return $this->length ?? null;
    }

    /**
     * @param float $length
     */
    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    public function setPropertiesFromArray(array $data): void
    {
        foreach ($data as $field => $value) {
            $this->{$field} = $value;
        }
    }

    public function save(): void
    {
        // Switch between UPDATE AND INSERT if video.id is set or not
        if (null === $this->getId()) {
            $query = 'INSERT INTO video (name) VALUES (?)';
            $data = [$this->getName()];
        } else {
            $query = 'UPDATE video SET name = ? WHERE id = ?';
            $data = [$this->getName(), $this->getId()];
        }

        try {
            static::getDatabase()
                ->prepare($query)
                ->execute($data)
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
