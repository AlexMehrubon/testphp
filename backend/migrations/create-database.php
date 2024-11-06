<?php

$host = 'mysql';
$user = 'album';
$password = 'pass';
$dbname = 'album_db';
$pdo = null;
try {
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "База данных '$dbname' успешно создана или уже существует.\n";
    $pdo->exec("USE $dbname");


    $pdo->beginTransaction();
    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(26) NOT NULL,
            password VARCHAR(26) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS albums (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            title VARCHAR(256) NOT NULL,
            description VARCHAR(256) NOT NULL,
            CONSTRAINT user_id FOREIGN KEY (user_id) REFERENCES users (id)
                ON UPDATE CASCADE ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            text TEXT NOT NULL,
            user_id INT NOT NULL,
            created_time DATETIME NOT NULL,
            CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users (id)
        );

        CREATE TABLE IF NOT EXISTS images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(256) NOT NULL,
            description VARCHAR(256) NOT NULL,
            file_path TEXT NOT NULL,
            album_id INT NOT NULL,
            CONSTRAINT images_albums_id_fk FOREIGN KEY (album_id) REFERENCES albums (id)
                ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS photos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            photo_path TEXT NOT NULL,
            album_id INT NULL,
            CONSTRAINT album_id FOREIGN KEY (album_id) REFERENCES albums (id)
        );
    ";

    $pdo->exec($sql);
    $pdo->commit();
    echo "Таблицы успешно созданы.\n";

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Ошибка: " . $e->getMessage() . "\n";
}