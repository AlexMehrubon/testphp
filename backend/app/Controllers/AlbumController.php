<?php

namespace App\Controllers;

use App\Models\Album;
use App\Repositories\AlbumRepository;
use App\Traits\JsonResponsableTrait;

class AlbumController
{
    use JsonResponsableTrait;

    private AlbumRepository $albumRepository;

    public function __construct()
    {
        $this->albumRepository = new AlbumRepository();
    }

    /**
     * @param array $request
     * @return string
     */
    public function index(array $request): string
    {
        $albums = isset($request['user_id'])
            ? $this->albumRepository->getByUserId((int)$request['user_id'])
            : $this->albumRepository->getAll();

        return $this->jsonResponse(['status' => 'success', 'message' => $albums]);
    }

    /**
     * @param array $request
     * @return string
     */
    public function store(array $request): string
    {
        if (empty($request['jsonData']['title']) || empty($request['jsonData']['description'])) {
            return $this->jsonResponse(['status' => 'failed', 'message' => 'Title and description are required'], 400);
        }

        $title = $request['jsonData']['title'];
        $description = $request['jsonData']['description'];
        $user_id = $_SESSION['user']['id'];
        $album = new Album($title, $description, $user_id);

        if ($this->albumRepository->create($album)) {
            return $this->jsonResponse(['status' => 'success', 'message' => $album]);
        }

        return $this->jsonResponse(['status' => 'failed', 'message' => 'Failed to create album'], 500);
    }

    /**
     * @param array $request
     * @return string
     */
    public function update(array $request): string
    {
        if (empty($request['id'])) {
            return $this->jsonResponse(['status' => 'failed', 'message' => 'Id is required'], 400);
        }
        return $this->albumRepository->update($request)
            ? $this->jsonResponse(['status' => 'success', 'message' => 'Album deleted'])
            : $this->jsonResponse(['status' => 'failed', 'message' => 'Failed!'], 404);
    }


    /**
     * @param array $request
     * @return string
     */
    public function destroy(array $request): string
    {
        if (empty($request['id'])) {
            return $this->jsonResponse(['status' => 'failed', 'message' => 'Album ID is required'], 400);
        }
        return $this->albumRepository->destroy((int)$request['id'])
            ? $this->jsonResponse(['status' => 'success', 'message' => 'Album updated'])
            : $this->jsonResponse(['status' => 'failed', 'message' => 'Album doesn\'t exists'], 404);

    }
}





