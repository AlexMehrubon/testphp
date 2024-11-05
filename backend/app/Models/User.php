<?php

namespace App\Models;

class User
{


    /**
     * @param string $name
     * @param string $password
     * @param int|null $id
     */
    public function __construct(
        public string $name,
        public string $password,
        public ?int $id = null
    )
    {
    }


    /**
     * @return array{name: string, password: string}
     */
    public function __serialize(): array
    {
        return [
            'name' => $this->name,
            'password' => $this->password,
            'id' => $this->id
        ];
    }

    /**
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->password = $data['password'];
        $this->id = $data['id'];
    }

}