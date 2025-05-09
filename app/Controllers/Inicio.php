<?php

namespace App\Controllers;

use App\Libraries\SpanishErrorsLibrary;
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
    
    private function generarTabla($arreglo,$tabla,$nColumnas,$ids=null){
        $i=0;
        if($ids!=null){
            $tabla.="<th style='width: 4.3rem;'></th>";
        }
        $tabla.="</thead>
                <tbody>";
        foreach($arreglo as $valor){
            $tabla .= '<tr>';
            foreach($valor as $item){
                $tabla.='<td>'. $item.' </td>';
            }
            if($ids!=null)if(!empty($ids)){
                if(isset($valor["nombreMascota"])){$metodoModificar="modificar_mascota";$metodoEliminar="eliminar_mascota";}
                if(isset($valor["nombreAmo"])){$metodoModificar="modificar_autor";$metodoEliminar="eliminar_autor";}
                if(isset($valor["nombreVeterinario"])){$metodoModificar="modificar_veterinario";$metodoEliminar="eliminar_veterinario";}
                if(isset($valor["fechaFinAmoMascota"]))if($valor["fechaFinAmoMascota"]==""){$metodoFinalizar="finalizar_relacion";}
                $tabla.='<td><div class="dropdown options">
                            <button class="btn dropdown-toggle dark btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                            <ul class="dropdown-menu dark">';
                if(isset($metodoModificar) && !isset($valor["fechaFinAmoMascota"]))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url("inicio/".$metodoModificar."/".$ids[$i]["id"]).'">Modificar</a>
                                </li>';
                if(isset($metodoFinalizar))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url("inicio/".$metodoFinalizar."/".$ids[$i]["id"]).'">Finalizar</a>
                                </li>';
                if(isset($metodoEliminar))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url("inicio/".$metodoEliminar."/".$ids[$i]["id"]).'">Eliminar</a>
                                </li>';
                $tabla.='</ul>
                         </div></td>';
            }
            $tabla.='</tr>';
            $i++;
        }
        if(sizeof($arreglo)<10){
            for($i=0;$i<(10-sizeof($arreglo));$i++){
                $tabla.='<tr>';
                for($j=0;$j<$nColumnas;$j++){
                    $tabla.="<td> </td>";
                }
                if($ids!=null)$tabla.="<td> </td>";
                $tabla.='</tr>';
            }
        }
        $tabla .= '</tbody> </table>';
        return $tabla;
    }
    public function mascotas(){
        $mascotas=new MascotaModel();
        try{
            $mascotasVivas=$mascotas->getAllMascotasVivas();
            $idMascotas=$mascotas->select("idMascota AS id")->findAll();
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
                    <th style="width: 9rem;">Fecha Alta</th>
                    <th style="width: 10rem;">Fecha Defuncion</th>
        ';
        if(isset($mascotasVivas) && isset($idMascotas)){
            for($i=0; $i<sizeof($mascotasVivas); $i++){
                $mascotasVivas[$i]["fechaAltaMascota"]=substr($mascotasVivas[$i]["fechaAltaMascota"],0,-9);
                if($mascotasVivas[$i]["fechaDefuncionMascota"]!=null){
                    $mascotasVivas[$i]["fechaDefuncionMascota"]=substr($mascotasVivas[$i]["fechaDefuncionMascota"],0,-9);
                }
            }
            $tabla=$this->generarTabla($mascotasVivas,$tabla,6,$idMascotas);
        }else{
            $tabla=$this->generarTabla([],$tabla,6);
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
                        <th style="width: 10rem;">Fecha Inicio Relacion</th>
                        <th style="width: 10rem;">Fecha Fin Relacion</th>
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
                $idMascotas= $amoMascotaModel->getAllIdAmoMascotas($amo);
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
                        <th style="width: 10rem;">Fecha Inicio Relacion</th>
                        <th style="width: 10rem;">Fecha Fin Relacion</th>
            ';
            if(isset($mascotas) && isset($idMascotas)){
                for($i=0; $i<sizeof($mascotas); $i++){
                    $mascotas[$i]["fechaInicioAmoMascota"]=substr($mascotas[$i]["fechaInicioAmoMascota"],0,-9);
                    if($mascotas[$i]["fechaFinAmoMascota"]!=null){
                        $mascotas[$i]["fechaFinAmoMascota"]=substr($mascotas[$i]["fechaFinAmoMascota"],0,-9);
                    }else{
                        $mascotas[$i]["fechaFinAmoMascota"]="";
                    }
                }
                $tabla=$this->generarTabla($mascotas,$tabla,6,$idMascotas);
            }else{
                $tabla=$this->generarTabla([],$tabla,6);
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
                        <th style="width: 10rem;">Fecha Inicio Relacion</th>
                        <th style="width: 10rem;">Fecha Fin Relacion</th>
            ';
            $tabla=$this->generarTabla([],$tabla,5);
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
                $idAmos = $amoMascotaModel->getAllIdMascotaAmos($mascota);
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Telefono</th>
                        <th style="width: 10rem;">Fecha Inicio Relacion</th>
                        <th style="width: 10rem;">Fecha Fin Relacion</th>
            ';
            if(isset($amos) && isset($idAmos)){
                for($i=0; $i<sizeof($amos); $i++){
                    $amos[$i]["fechaInicioAmoMascota"]=substr($amos[$i]["fechaInicioAmoMascota"],0,-9);
                    if($amos[$i]["fechaFinAmoMascota"]!=null){
                        $amos[$i]["fechaFinAmoMascota"]=substr($amos[$i]["fechaFinAmoMascota"],0,-9);
                    }else{
                        $amos[$i]["fechaFinAmoMascota"]="";
                    }
                }
                $tabla=$this->generarTabla($amos,$tabla,5,$idAmos);
            }else{
                $tabla=$this->generarTabla([],$tabla,5);
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
            $idAmos=$amosModel->select("idAmo AS id")->findAll();
        } catch(Error $e){
            return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
        }
        $tabla = '
        <table>
            <thead>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Telefono</th>
                    <th style="width: 9rem;">Fecha Alta</th>
        ';
        if(isset($amos) && isset($idAmos)){
            for($i=0; $i<sizeof($amos); $i++){
                $amos[$i]["fechaAltaAmo"]=substr($amos[$i]["fechaAltaAmo"],0,-9);
            }
            $tabla=$this->generarTabla($amos,$tabla,4,$idAmos);
        }else{
            $tabla=$this->generarTabla([],$tabla,4);
        }
        $data["tipoTabla"]="Amos";
        $data['table']=$tabla;
        return view('inicioView', $data);
    }

    public function veterinarios(){
        $veterinariosModel=new VeterinarioModel();
        try{
            $veterinarios=$veterinariosModel->getAllVeterinarios();
            $idVeterinados=$veterinariosModel->select("idVeterinario AS id")->findAll();
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
                    <th style="width: 9rem;">Fecha Ingreso</th>
                    <th style="width: 9rem;">Fecha Egreso</th>
        ';
        if(isset($veterinarios) && isset($idVeterinados)){
            for($i=0; $i<sizeof($veterinarios); $i++){
                $veterinarios[$i]["fechaIngresoVeterinario"]=substr($veterinarios[$i]["fechaIngresoVeterinario"],0,-9);
                if($veterinarios[$i]["fechaEgresoVeterinario"]!=null){
                    $veterinarios[$i]["fechaEgresoVeterinario"]=substr($veterinarios[$i]["fechaEgresoVeterinario"],0,-9);
                }
            }
            $tabla=$this->generarTabla($veterinarios,$tabla,6,$idVeterinados);
        }else{
            $tabla=$this->generarTabla([],$tabla,6);
        }
        $data["tipoTabla"]="Veterinarios";
        $data['table']=$tabla;
        return view('inicioView', $data);
    }

    public function altaMascotas(){
        helper(['form','spanishErrors_helper']);   
        $rules = [
            'nombreMascota' => 'required|min_length[4]|max_length[255]|alpha',
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
        helper(['form', 'spanishErrors_helper']);   
        $rules = [
            'nombreAmo' => 'required|min_length[5]|max_length[30]',
            'apellidoAmo' => 'required|min_length[4]|max_length[30]',
            'telefonoAmo' => 'required|regex_match[/^(\\+54)?[0-9]{10,12}$/]',
        ];
        $validacion = service('validations');
        $validacion->setRules($rules,spanishErrorMessages($rules));
        if (!$validacion->withRequest($this->request)->run()) {
            return redirect()->to(base_url().'inicio/mascotas')->withInput()->with('errors', $validacion->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
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
        helper(['form', 'SpanishErrors_helper']);   
        $rules = [
            'nombreVeterinario' => 'required|min_length[5]|max_length[30]',
            'apellidoVeterinario' =>  'required|min_length[4]|max_length[30]',
            'especialidadVeterinario' => 'required|min_length[4]|max_length[30]',
            'telefonoVeterinario' => 'required|telefono_valido[telefonoVeterinario]',   
        ];
        $validacion = service('validation');
        $validacion->setRules($rules,spanishErrorMessages($rules));
        if (!$validacion->withRequest($this->request)->run()) {
            return redirect()->to(base_url().'inicio/veterinarios')->withInput()->with('errors', $validacion->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
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
