<?php

namespace App\Repositories;

use App\Database;
use App\Models\User;
use PDO;

class UserRepository
{
    private Database $database;

    /**
     *
     */
    public function __construct(){
        $this->database = Database::getInstance();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getByName(string $name): mixed
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function getAll() : array
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users ?: [];
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("INSERT INTO users (name, password) VALUES (:name, :password)");
        return $stmt->execute(['name' => $user->name, 'password' => $user->password]);
    }

    public function getById(int $id)
    {
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}