<?php

namespace App\Controllers;

use App\Models\Image;
use App\Repositories\ImagerRepository;
use App\Traits\JsonResponsableTrait;

class ImageController
{
    use JsonResponsableTrait;
    const string UPLOAD_DIR = '../uploads/';

    private ImagerRepository $imagerRepository;

    public function __construct()
    {
        $this->imagerRepository = new ImagerRepository();
    }

    /**
     * @param array $request
     * @return string
     */
    public function index(array $request): string
    {
        $images = $this->imagerRepository->getAllByAlbumId($request['id']);
        return $this->jsonResponse(['status' => 'success', 'message' => $images]);
    }


    /**
     * @param array $request
     * @return string
     */
    public function store(array $request): string{
        if (isset($request['files']['image']) && $request['files']['image']['error'] == UPLOAD_ERR_OK) {
            $image = $request['files']['image'];
            $title = $request['title'];
            $description = $request['description'];

            $uploadFileName = basename($image['name']);
            $uploadFilePath = self::UPLOAD_DIR . $uploadFileName;

            if (move_uploaded_file($image['tmp_name'], $uploadFilePath)) {
                $image = new Image($title, $description, $uploadFilePath, $request['id']);
                $this->imagerRepository->create($image);
                return $this->jsonResponse(['status' => 'success', 'message' => $image]);
            }

            return $this->jsonResponse(['status' => 'error', 'message' => "Error uploading file"], 400);

        }
        return $this->jsonResponse(['status' => 'failed', 'message' => "Error uploading file"],  400);
    }
}