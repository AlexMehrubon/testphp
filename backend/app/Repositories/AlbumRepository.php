<?php

namespace App\Repositories;

use App\Database;
use App\Models\Album;
use PDO;

class AlbumRepository
{
    private Database $database;

    /**
     *
     */
    public function __construct(){
        $this->database = Database::getInstance();
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM albums");
        $stmt->execute();

        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $albums ?: [];
    }


    /**
     * @param Album $album
     * @return bool
     */
    public function create(Album $album) : bool
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("INSERT INTO albums (title, description, user_id) VALUES (:title, :description, :user_id)");
        return $stmt->execute(['title' => $album->title, 'description' => $album->description, 'user_id' => $album->user_id]);
    }

    /**
     * @param int $user_id
     * @return array
     */
    public function getByUserId(int $user_id) : array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM albums WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return true
     */
    public function destroy(int $id): bool
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("DELETE FROM albums WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * @param array $request
     * @return bool
     */
    public function update(array $request): bool
    {
        $id = $request['id'];
        $title = $request['jsonData']['title'];
        $description = $request['jsonData']['description'];

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("UPDATE albums SET title = :title, description = :description WHERE id = :id");
        return $stmt->execute(['title' => $title, 'description' => $description, 'id' => $id]);
    }
}