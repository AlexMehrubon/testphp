<?php

namespace App\Controllers;

use App\Repositories\UserRepository;

class UserController
{

    private UserRepository $userRepository;

    /**
     *
     */
    public function __construct(){
        $this->userRepository = new UserRepository();
    }


    /**
     * @param $request
     * @return string
     */
    public function index($request) : string{
        if (isset($request['user_id'])) {
            $users = $this->userRepository->getById((int) $request['user_id']);
        }
        else
            $users = $this->userRepository->getAll();
        return json_encode(['status' => 'success', 'message' => $users]);
    }

}