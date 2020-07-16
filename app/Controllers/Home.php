<?php

namespace App\Controllers;

use App\Models\User;

class Home extends BaseController
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            return redirect()->to('/login');
        }
        return $this->renderPage('homepage', User::getUsers());
    }

    //--------------------------------------------------------------------

}
