<?php

namespace App\Models;

class Album
{
    public function __construct(
        public string $title,
        public string $description,
        public int $user_id,
    )
    {
    }
}