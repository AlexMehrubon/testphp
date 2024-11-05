<?php

namespace App\Repositories;

use App\Database;
use App\Models\Image;
use App\Traits\JsonResponsableTrait;
use PDO;

class ImagerRepository
{
    private Database $database;

    /**
     *
     */
    public function __construct()
    {
        $this->database = Database::getInstance();
    }


    /**
     * @param int $album_id
     * @return array
     */
    public function getAllByAlbumId(int $album_id): array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM images WHERE album_id = :album_id");
        $stmt->execute(['album_id' => $album_id]);



        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * @param Image $image
     * @return bool
     */
    public function create(Image $image): bool
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("INSERT INTO images (title, description, file_path, album_id) VALUES (:title, :description, :file_path, :album_id)");
        return $stmt->execute(['title' => $image->title, 'description' => $image->description, 'file_path' => $image->path, 'album_id' => $image->album_id]);

    }

    /**
     * @param mixed $id
     * @return array | false
     */
    public function getById(mixed $id) : array | false
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM images WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}