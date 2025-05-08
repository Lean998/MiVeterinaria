<?php

namespace App\Controllers;
use App\Models\MiVeterinariaDBModel;
class MiVeterinariaDB extends BaseController
{
    public function index(){
        if(new MiVeterinariaDBModel())redirect()->to(base_url()."inicio");else log_message('debug', 'DB no Creada');
    }
}