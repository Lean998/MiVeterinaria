<?php

namespace App\Controllers;
use App\Models\MascotaModel;
use App\Models\AmoModel;
use App\Models\AmoMascotaModel;
use App\Models\VeterinarioModel;
use CodeIgniter\I18n\Time;
use Error;

class Inicio extends BaseController
{
    public function index(): string
    {
        return view('inicioView');
    }
    
    private function generarTabla($arreglo,$tabla,$nColumnas){
        foreach($arreglo as $valor){
            $tabla .= '<tr>';
            foreach($valor as $item){
                $tabla.='<td>'. $item.' </td>';
            }
            $tabla.='<tr>';
        }
        if(sizeof($arreglo)<10){
            for($i=0;$i<(10-sizeof($arreglo));$i++){
                $tabla.='<tr>';
                for($j=0;$j<$nColumnas;$j++){
                    $tabla.="<td> </td>";
                }
                $tabla.='<tr>';
            }
        }
        $tabla .= '</tbody> </table>';
        return $tabla;
    }
    public function mascotas(){
        $mascotas=new MascotaModel();
        try{
            $mascotasVivas=$mascotas->getAllMascotasVivas();
        } catch(Error $e){
            return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
        }
        $tabla = '
        <table>
            <thead>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Especie</th>
                    <th>Raza</th>
            </thead>
            <tbody>
        ';
        if(isset($mascotasVivas)){
            $tabla=$this->generarTabla($mascotasVivas,$tabla,4);
        }else{
            $tabla=$this->generarTabla([],$tabla,4);
        }
        $data['table']=$tabla;
        $data["tipoTabla"]="Mascotas";
        return view('inicioView', $data);
    }

    public function amoMascotas() {
        $amo=$this->request->getPost("listaAmos");
        if(!isset($amo)){
            $amoModel=new AmoModel();
            try{
                $amos=$amoModel->getAllAmosList();
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                </thead>
                <tbody>
            ';
            $tabla=$this->generarTabla([],$tabla,6);
            $data=[
                "mascota_amos_list"=>$amos,
                "table"=>$tabla
            ];
            return view("inicioView",$data);
        }
        else{
            $amoModel=new AmoModel();
            try{
                $amos=$amoModel->getAllAmosList();
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $data["mascota_amos_list"]=$amos;
            $amoMascotaModel = new AmoMascotaModel();
            try{
                $mascotas = $amoMascotaModel->getAllAmoMascotas($amo);
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                </thead>
                <tbody>
            ';
            if(!$mascotas){
                $tabla=$this->generarTabla([],$tabla,6);
            }else{
                $tabla=$this->generarTabla($mascotas,$tabla,6);
            }
            $data['table'] = $tabla;
            $data["tipoTabla"]="AmoMascotas";
            return view('inicioView', $data);
        }
    }

    public function mascotaAmos() {
        $mascota=$this->request->getPost("ListaMascotas");
        if(!isset($mascota)){
            $mascotaModel=new MascotaModel();
            try{
                $mascotas=$mascotaModel->getAllMascotasList();
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Telefono</th>
                        <th>Fecha de Alta</th>
                </thead>
                <tbody>
            ';
            $tabla=$this->generarTabla([],$tabla,4);
            $data=[
                "amo_mascotas_list"=>$mascotas,
                "table"=>$tabla
            ];
            return view("inicioView",$data);
        }
        else{
            $mascotaModel=new MascotaModel();
            try{
                $mascotas=$mascotaModel->getAllMascotasList();
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $data["amo_mascotas_list"]=$mascotas;
            $amoMascotaModel = new AmoMascotaModel();
            try{
                $amos = $amoMascotaModel->getAllMascotaAmos($mascota);
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Telefono</th>
                        <th>Fecha de Alta</th>
                </thead>
                <tbody>
            ';
            if(!$amos){
                $tabla=$this->generarTabla([],$tabla,4);
            }else{
                $tabla=$this->generarTabla($amos,$tabla,4);
            }
            $data['table'] = $tabla;
            $data["tipoTabla"]="MascotaAmos";
            return view('inicioView', $data);
        }
    }

    public function amos(){
        $amosModel=new AmoModel();
        try{
            $amos=$amosModel->getAllAmos();
        } catch(Error $e){
            return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
        }
        $tabla = '
        <table>
            <thead>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Telefono</th>
            </thead>
            <tbody>
        ';
        if(isset($amos)){
            $tabla=$this->generarTabla($amos,$tabla,3);
        }else{
            $tabla=$this->generarTabla([],$tabla,3);
        }
        $data["tipoTabla"]="Amos";
        $data['table']=$tabla;
        return view('inicioView', $data);
    }

    public function veterinarios(){
        $veterinariosModel=new VeterinarioModel();
        try{
            $veterinarios=$veterinariosModel->getAllVeterinarios();
        } catch(Error $e){
            return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
        }
        $tabla = '
        <table>
            <thead>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Especialidad</th>
                    <th>Telefono</th>
            </thead>
            <tbody>
        ';
        if(isset($amos)){
            $tabla=$this->generarTabla($veterinarios,$tabla,4);
        }else{
            $tabla=$this->generarTabla([],$tabla,4);
        }
        $data["tipoTabla"]="Veterinarios";
        $data['table']=$tabla;
        return view('inicioView', $data);
    }

    public function altaMascotas(){
        helper(['form','spanishErrors_helper']);   
        $rules = [
            'nombreMascota' => 'required|min_length[2]|max_length[255]|alpha',
            'especieMascota' => 'required|min_length[4]|max_length[30]|alpha',
            'razaMascota' => 'required|min_length[4]|max_length[30]|alpha',
            'edadMascota' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];
        $validacion = service('validation');
        $validacion->setRules($rules,spanishErrorMessages($rules));
        if (!$validacion->withRequest($this->request)->run()) {
            return redirect()->to(base_url().'inicio/mascotas')->withInput()->with('errors', $validacion->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
        }

        $mascotaModel = new MascotaModel();
        $data = [
            'nombreMascota' => $this->request->getPost('nombreMascota'),
            'especieMascota' => $this->request->getPost('especieMascota'),
            'razaMascota' => $this->request->getPost('razaMascota'),
            'edadMascota' => $this->request->getPost('edadMascota'),
            'fechaAltaMascota' => Time::now()->toDateTimeString(),
        ];
        if($mascotaModel->save($data)){
            return redirect()->to(base_url().'public/index.php/inicio/mascotas')->with('success', 'Mascota registrada con exito!');
        } else{
            return redirect()->to(base_url().'public/index.php/inicio/mascotas')->with('error', 'Ocurrio un error al registrar la mascota, intente de nuevo mas tarde.');
        }
    }

    public function altaAmos(){
        helper(['form']);   
        $rules = [
            'nombreAmo' => [
            'rules' => 'required|min_length[5]|max_length[30]',
            'errors' => [
                'required' => 'El nombre del amo es obligatorio.',
                'min_length' => 'El nombre debe tener al menos 5 caracteres.',
                'max_length' => 'El nombre no puede superar los 30 caracteres.',
                ]
            ],
            'apellidoAmo' => [
                'rules' => 'required|min_length[4]|max_length[30]',
                'errors' => [
                    'required' => 'El apellido es obligatorio.',
                    'min_length' => 'El apellido debe tener al menos 4 caracteres.',
                    'max_length' => 'El apellido no puede superar los 30 caracteres.',
                ]
            ],
            
            'telefonoAmo' => [
                'rules' => 'required|regex_match[/^(\\+54)?[0-9]{10,12}$/]',
                'errors' => [
                    'required' => 'El teléfono es obligatorio.',
                    'regex_match' => 'Ingrese un número de teléfono válido. Ej: +542664123456 o 2664123456.',
                ]
            ]   
        ];
        if (!$this->validate($rules)) {
            return redirect()->to(substr(base_url(), 0, -17).'public/index.php/inicio/amos')->withInput()->with('errors', $this->validator->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
        }

        $amosModel = new AmoModel();
        $data = [
            'nombreAmo' => $this->request->getPost('nombreAmo'),
            'apellidoAmo' => $this->request->getPost('apellidoAmo'),
            'telefonoAmo' => $this->request->getPost('telefonoAmo'),
            'fechaAltaAmo' => Time::now()->toDateTimeString(),
        ];
        if($amosModel->save($data)){
            return redirect()->to(substr(base_url(), 0, -17).'public/index.php/inicio/amos')->with('success', 'Amo registrado con exito!');
        } else{
            return redirect()->to(substr(base_url(), 0, -17).'public/index.php/inicio/amos')->with('error', 'Ocurrio un error al registrar el amo, intente de nuevo mas tarde.');
        }
    }

    public function altaVeterinarios(){
        helper(['form']);   
        $rules = [
            'nombreVeterinario' => [
            'rules' => 'required|min_length[5]|max_length[30]',
            'errors' => [
                'required' => 'El nombre del veterinario es obligatorio.',
                'min_length' => 'El nombre debe tener al menos 5 caracteres.',
                'max_length' => 'El nombre no puede superar los 30 caracteres.',
                ]
            ],
            'apellidoVeterinario' => [
                'rules' => 'required|min_length[4]|max_length[30]',
                'errors' => [
                    'required' => 'El apellido es obligatorio.',
                    'min_length' => 'El apellido debe tener al menos 4 caracteres.',
                    'max_length' => 'El apellido no puede superar los 30 caracteres.',
                ]
            ],
            'especialidadVeterinario' => [
                'rules' => 'required|min_length[4]|max_length[30]',
                'errors' => [
                    'required' => 'La especialidad es obligatoria.',
                    'min_length' => 'La especialidad debe tener al menos 4 caracteres.',
                    'max_length' => 'La especialidad no puede superar los 30 caracteres.',
                ]
            ],
            'telefonoVeterinario' => [
                'rules' => 'required|regex_match[/^(\\+54)?[0-9]{10,12}$/]',
                'errors' => [
                    'required' => 'El teléfono es obligatorio.',
                    'regex_match' => 'Ingrese un número de teléfono válido. Ej: +542664123456 o 2664123456.',
                ]
            ]   
        ];
        if (!$this->validate($rules)) {
            return redirect()->to(substr(base_url(), 0, -17).'public/index.php/inicio/veterinarios')->withInput()->with('errors', $this->validator->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
        }

        $veterinarioModel = new VeterinarioModel();
        $data = [
            'nombreVeterinario' => $this->request->getPost('nombreVeterinario'),
            'apellidoVeterinario' => $this->request->getPost('apellidoVeterinario'),
            'especialidadVeterinario' => $this->request->getPost('especialidadVeterinario'),
            'fechaIngresoVeterinario' => Time::now()->toDateTimeString(),
            'telefonoVeterinario' => $this->request->getPost('telefonoVeterinario'),
        ];
        if($veterinarioModel->save($data)){
            return redirect()->to(substr(base_url(), 0, -17).'public/index.php/inicio/veterinarios')->with('success', 'Veterinario registrado con exito!');
        } else{
            return redirect()->to(substr(base_url(), 0, -17).'public/index.php/inicio/veterinarios')->with('error', 'Ocurrio un error al registrar al veterinario, intente de nuevo mas tarde.');
        }
    }
    
}
