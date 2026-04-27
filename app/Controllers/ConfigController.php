<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PHPUnit\Framework\Constraint\IsFalse;

class ConfigController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Travel Order | Configurations',
            'page' => 'Configuration'
        ];
        if (!session()->get('isLoggedIn')) {
            return $this->renderError(
                $this->errorHandler->unauthorized()
            );
        }
        if (session()->get('role') !== "admin") {
            return $this->renderError(
                $this->errorHandler->forbidden('Admins only.')
            );
        }
        return view('admin/config',$data);
    }
}
