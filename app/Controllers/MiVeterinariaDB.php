<?php

namespace App\Controllers;
use App\Models\MiVeterinariaDBModel;
class MiVeterinariaDB extends BaseController
{
    public function index(){
        if(new MiVeterinariaDBModel())return redirect()->to(base_url()."inicio")->with("success","Base de Datos Creada!");else log_message('debug', 'DB no Creada'); return redirect()->to(base_url()."inicio")->with("error","Error al crear la Base de Datos!<br>Intentelo nuevamente.");
    }
}