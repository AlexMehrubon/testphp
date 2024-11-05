<?php

namespace App\Models;

class Image
{
    public function __construct(
        public string $title,
        public string $description,
        public string $path,
        public int $album_id
    )
    {
    }
}