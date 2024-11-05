<?php

namespace App\Controllers;

use App\Repositories\ImagerRepository;
use App\Traits\JsonResponsableTrait;

class UploadController
{
    use JsonResponsableTrait;
    const string UPLOAD_DIR = '../uploads/';

    private ImagerRepository $imagerRepository;

    public function __construct()
    {
        $this->imagerRepository = new ImagerRepository();
    }

    /**
     * @param $request
     * @return string
     */
    public function getImage($request) : string
    {
        $image = $this->imagerRepository->getById($request['id']);
        if (!$image)
            return $this->jsonResponse(['status' => 'failed', 'message' => 'File not found.'], 404);

        $filePath = self::UPLOAD_DIR . $image['file_path'];
        if (file_exists($filePath)) {
            $fileType = mime_content_type($filePath);
            header("Content-Type: $fileType");
            readfile($filePath);
            exit;
        } else {
            return $this->jsonResponse(['status' => 'failed', 'message' => 'File not found.'], 404);
        }
    }
}