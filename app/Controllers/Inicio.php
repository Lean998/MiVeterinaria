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
    
    private function generarTabla($arreglo,$tabla,$nColumnas,&$validNew,$ids=null){
        $i=0;
        if($ids!=null){
            $tabla.="<th style='width: 4.3rem;'></th>";
        }
        $tabla.="</thead>
                <tbody>";
        foreach($arreglo as $valor){
            if(isset($valor["id"]))$j=0;
            $tabla .= '<tr>';
            foreach($valor as $item){
                if(isset($j))if($j==0){$j++;continue;}
                $tabla.='<td>'. $item.' </td>';
            }
            if($ids!=null)if(!empty($ids)){
                if(isset($valor["nombreMascota"])){$metodoModificar="modificar_mascota";$metodoEliminar="eliminar_mascota";}
                if(isset($valor["nombreAmo"])){$metodoModificar="modificar_autor";$metodoEliminar="eliminar_autor";}
                if(isset($valor["nombreVeterinario"])){$metodoModificar="modificar_veterinario";$metodoEliminar="eliminar_veterinario";}
                if(isset($valor["fechaFinAmoMascota"]))if($valor["fechaFinAmoMascota"]==""){$metodoFinalizar="finalizar_relacion";$validNew=false;}
                $tabla.='<td><div class="dropdown options">
                            <button class="btn dropdown-toggle dark btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                            <ul class="dropdown-menu dark">';
                if(isset($valor["nombreMascota"]) && isset($valor["fechaAltaMascota"]))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url("inicio/new_relacion_mascota_amo/".$ids[$i]["id"]).'">Nueva Relacion</a>
                                </li>';
                if(isset($valor["nombreAmo"]) && isset($valor["fechaAltaAmo"]))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url("inicio/new_relacion_amo_mascota/".$ids[$i]["id"]).'">Nueva Relacion</a>
                                </li>';
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
        $validNew=true;
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
        ';
        if(isset($mascotasVivas) && isset($idMascotas)){
            for($i=0; $i<sizeof($mascotasVivas); $i++){
                $mascotasVivas[$i]["fechaAltaMascota"]=substr($mascotasVivas[$i]["fechaAltaMascota"],0,-9);
            }
            $tabla=$this->generarTabla($mascotasVivas,$tabla,5,$validNew,$idMascotas);
        }else{
            $tabla=$this->generarTabla([],$tabla,5,$validNew);
        }
        $data['table']=$tabla;
        if(!$validNew)$data["invalidNew"]=true;
        $data["tipoTabla"]="Mascotas";
        return view('inicioView', $data);
    }

    public function amoMascotas() {
        $validNew=true;
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
            $tabla=$this->generarTabla([],$tabla,6,$validNew);
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
                $tabla=$this->generarTabla($mascotas,$tabla,6,$validNew,$idMascotas);
            }else{
                $tabla=$this->generarTabla([],$tabla,6,$validNew);
            }
            $data['table'] = $tabla;
            $data["tipoTabla"]="AmoMascotas";
            return view('inicioView', $data);
        }
    }

    public function mascotaAmos() {
        $validNew=true;
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
            $tabla=$this->generarTabla([],$tabla,5,$validNew);
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
                $tabla=$this->generarTabla($amos,$tabla,5,$validNew,$idAmos);
            }else{
                $tabla=$this->generarTabla([],$tabla,5,$validNew);
            }
            $data['table'] = $tabla;
            if(!$validNew)$data["invalidNew"]=true;
            $data["tipoTabla"]="MascotaAmos";
            return view('inicioView', $data);
        }
    }

    public function amos(){
        $validNew=true;
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
            $tabla=$this->generarTabla($amos,$tabla,4,$validNew,$idAmos);
        }else{
            $tabla=$this->generarTabla([],$tabla,4,$validNew);
        }
        $data["tipoTabla"]="Amos";
        if(!$validNew)$data["invalidNew"]=true;
        $data['table']=$tabla;
        return view('inicioView', $data);
    }

    public function veterinarios(){
        $validNew=true;
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
            $tabla=$this->generarTabla($veterinarios,$tabla,6,$validNew,$idVeterinados);
        }else{
            $tabla=$this->generarTabla([],$tabla,6,$validNew);
        }
        $data["tipoTabla"]="Veterinarios";
        if(!$validNew)$data["invalidNew"]=true;
        $data['table']=$tabla;
        return view('inicioView', $data);
    }

    public function altaMascotas(){
        helper(['form','spanishErrors_helper']);   
        $rules = [
            'nombreMascota' => 'required|min_length[4]|max_length[255]|valid_alpha_space[nombreMascota]',
            'especieMascota' => 'required|min_length[4]|max_length[30]|valid_alpha_space[especieMascota]',
            'razaMascota' => 'required|min_length[4]|max_length[30]|valid_alpha_space[razaMascota]',
            'edadMascota' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];
        $conAmo=$this->request->getPost("conAmo");
        if(isset($conAmo)){
            $rules["conNombreAmo"]="required|min_length[5]|max_length[30]|valid_alpha_space[nombreAmo]";
            $rules["conApellidoAmo"]="required|min_length[4]|max_length[30]|valid_alpha_space[apellidoAmo]";
            $rules["conTelefonoAmo"]="required|telefono_valido[telefonoAmo]";
            $rules["conFechaNewRelMA"]="required|valid_date";
        }
        $validacion = service('validation');
        $validacion->setRules($rules,spanishErrorMessages($rules));
        if (!$validacion->withRequest($this->request)->run()) {
            return redirect()->to(base_url().'inicio/mascotas')->withInput()->with('errors', $validacion->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
        }
        try{
            $mascotaModel = new MascotaModel();
            $data = [
                'nombreMascota' => $this->request->getPost('nombreMascota'),
                'especieMascota' => $this->request->getPost('especieMascota'),
                'razaMascota' => $this->request->getPost('razaMascota'),
                'edadMascota' => $this->request->getPost('edadMascota'),
                'fechaAltaMascota' => Time::now()->toDateTimeString(),
            ];
            $idMascota=$mascotaModel->insert($data,true);
            if($idMascota){
                if(isset($conAmo)){
                    $amosModel = new AmoModel();
                    $data = [
                        'nombreAmo' => $this->request->getPost('conNombreAmo'),
                        'apellidoAmo' => $this->request->getPost('conApellidoAmo'),
                        'telefonoAmo' => $this->request->getPost('conTelefonoAmo'),
                        'fechaAltaAmo' => Time::now()->toDateTimeString(),
                    ];
                    $idAmo=$amosModel->insert($data,true);
                    if($idAmo){
                        $relAmoMasc=new AmoMascotaModel();
                        $data = [
                            'idAmo' => $idAmo,
                            'idMascota' => $idMascota,
                            'fechaInicioAmoMascota' => $this->request->getPost('conFechaNewRelMA'),
                        ];
                        if(!$relAmoMasc->insert($data)){
                            $relAmoMasc=null;
                            return redirect()->to(base_url()."inicio/mascotas")->with("error","Mascota y Amo registrados con exito<br>La relacion entre Mascota y Amo no se pudo crear");
                        }
                        return redirect()->to(base_url()."inicio/mascotas")->with("success","Mascota, Amo y su relacion han sido registrados con exito");
                    }else{
                        return redirect()->to(base_url()."inicio/mascotas")->with("error","Mascota registrada con exito<br>El Amo y la relacion entre Mascota y Amo no se pudieron crear");
                    }
                }
                return redirect()->to(base_url().'inicio/mascotas')->with('success', 'Mascota registrada con exito!');
            } else{
                return redirect()->to(base_url().'inicio/mascotas')->with('error', 'Ocurrio un error al registrar la mascota, intente de nuevo mas tarde.');
            }
        }catch(Error $e){
            return redirect()->to(base_url().'inicio')->with('error', 'Ocurrio un error inesperado. Estamos trabajando en ello.');
        }
    }

    public function altaAmos(){
        helper(['form', 'spanishErrors_helper']);   
        $rules = [
            'nombreAmo' => 'required|min_length[5]|max_length[30]|valid_alpha_space[nombreAmo]',
            'apellidoAmo' => 'required|min_length[4]|max_length[30]|valid_alpha_space[apellidoAmo]',
            'telefonoAmo' => 'required|telefono_valido[telefonoAmo]',
        ];
        $conMascota=$this->request->getPost("conMascota");
        if(isset($conMascota)){
            $rules['conNombreMascota'] = 'required|min_length[4]|max_length[255]|valid_alpha_space[nombreMascota]';
            $rules['conEspecieMascota'] = 'required|min_length[4]|max_length[30]|valid_alpha_space[especieMascota]';
            $rules['conRazaMascota'] = 'required|min_length[4]|max_length[30]|valid_alpha_space[razaMascota]';
            $rules['conEdadMascota'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules["conFechaNewRelAM"]="required|valid_date";
        }
        $validacion = service('validation');
        $validacion->setRules($rules,spanishErrorMessages($rules));
        if (!$validacion->withRequest($this->request)->run()) {
            return redirect()->to(base_url().'inicio/amos')->withInput()->with('errors', $validacion->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
        }
        try{
            $amosModel = new AmoModel();
            $data = [
                'nombreAmo' => $this->request->getPost('nombreAmo'),
                'apellidoAmo' => $this->request->getPost('apellidoAmo'),
                'telefonoAmo' => $this->request->getPost('telefonoAmo'),
                'fechaAltaAmo' => Time::now()->toDateTimeString(),
            ];
            $idAmo=$amosModel->insert($data,true);
            if($idAmo){
                if(isset($conMascota)){
                    $mascotaModel = new MascotaModel();
                    $data = [
                        'nombreMascota' => $this->request->getPost('conNombreMascota'),
                        'especieMascota' => $this->request->getPost('conEspecieMascota'),
                        'razaMascota' => $this->request->getPost('conRazaMascota'),
                        'edadMascota' => $this->request->getPost('conEdadMascota'),
                        'fechaAltaMascota' => Time::now()->toDateTimeString(),
                    ];
                    $idMascota=$mascotaModel->insert($data,true);
                    if($idMascota){
                        $relAmoMasc=new AmoMascotaModel();
                        $data = [
                            'idAmo' => $idAmo,
                            'idMascota' => $idMascota,
                            'fechaInicioAmoMascota' => $this->request->getPost('conFechaNewRelAM'),
                        ];
                        if(!$relAmoMasc->insert($data)){
                            $relAmoMasc=null;
                            return redirect()->to(base_url()."inicio/amos")->with("error","Amo y Mascota registrados con exito<br>La relacion entre Amo y Mascota no se pudo crear");
                        }
                        return redirect()->to(base_url()."inicio/amos")->with("success","Amo, Mascota y su relacion han sido registrados con exito");
                    }else{
                        return redirect()->to(base_url()."inicio/amos")->with("error","Amo registrado con exito<br>La mascota y la relacion entre Amo y Mascota no se pudieron crear");
                    }
                }
                return redirect()->to(base_url().'inicio/amos')->with('success', 'Amo registrado con exito!');
            } else{
                return redirect()->to(base_url().'inicio/amos')->with('error', 'Ocurrio un error al registrar el amo, intente de nuevo mas tarde.');
            }
        }catch(Error $e){
            return redirect()->to(base_url().'inicio')->with('error', 'Ocurrio un error inesperado. Estamos trabajando en ello.');
        }
    }

    public function altaVeterinarios(){
        helper(['form', 'SpanishErrors_helper']);   
        $rules = [
            'nombreVeterinario' => 'required|min_length[5]|max_length[30]|valid_alpha_space[nombreVeterinario]',
            'apellidoVeterinario' =>  'required|min_length[4]|max_length[30]|valid_alpha_space[apellidoVeterinario]',
            'especialidadVeterinario' => 'required|min_length[4]|max_length[30]|valid_alpha_space[especialidadVeterinario]',
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
            return redirect()->to(substr(base_url(), 0, -17).'inicio/veterinarios')->with('success', 'Veterinario registrado con exito!');
        } else{
            return redirect()->to(substr(base_url(), 0, -17).'inicio/veterinarios')->with('error', 'Ocurrio un error al registrar al veterinario, intente de nuevo mas tarde.');
        }
    }

    public function finalizarRelacion($idRel=null){
        helper(['form', 'SpanishErrors_helper']);
        $post=$this->request->getPost();
        if(empty($post)){
            return view("finalizarRelacionView",["idRel"=>$idRel]);
        }else{
            $post=$this->request->getPost(["motivoFinalRel","fechaFinalRel","idFinalRel"]);
            $rules=[
                "fechaFinalRel"=>"required|valid_date"
            ];
            $validacion=service("validation");
            $validacion->setRules($rules,spanishErrorMessages($rules));
            if(!$validacion->withRequest($this->request)->run()){
                return redirect()->to(base_url()."inicio/finalizar_relacion/".$post["idFinalRel"])->withInput()->with('errors', $validacion->getErrors());
            }
            try{
                $relAmoMasc=new AmoMascotaModel();
                if(!$relAmoMasc->update($post["idFinalRel"],["fechaFinAmoMascota"=>$post["fechaFinalRel"]])){
                    $relAmoMasc=null;
                    return redirect()->to(base_url()."inicio/finalizar_relacion/".$post["idFinalRel"])->with("error","Ocurrio un error al intentar finalizar la relacion");
                }
            }catch(Error $e){
                return redirect()->to(base_url()."inicio/finalizar_relacion/".$post["idFinalRel"])->with("error","Ocurrio un error al intentar finalizar la relacion");
            }
            if($post["motivoFinalRel"]=="Fallecimiento"){
                try{
                    $idMascota=$relAmoMasc->select("idMascota")->find($post["idFinalRel"]);
                    $relAmoMasc=null;
                }catch(Error $e){
                    return redirect()->to(base_url()."inicio/finalizar_relacion/".$post["idFinalRel"])->with("error","Relacion finalizada con exito.<br>No se pudo modificar la fecha de defuncion de la mascota.");
                }
                if(isset($idMascota)){
                    if(!empty($idMascota)){
                        try{
                            $mascota=new MascotaModel();
                            if(!$mascota->update($idMascota["idMascota"],["fechaDefuncionMascota"=>$post["fechaFinalRel"]])){
                                $mascota=null;
                                return redirect()->to(base_url()."inicio/finalizar_relacion/".$post["idFinalRel"])->with("error","Relacion finalizada con exito.<br>No se pudo modificar la fecha de defuncion de la mascota.");
                            }
                            $mascota=null;
                        }catch(Error $e){
                            return redirect()->to(base_url()."inicio/finalizar_relacion/".$post["idFinalRel"])->with("error","Relacion finalizada con exito.<br>No se pudo modificar la fecha de defuncion de la mascota.");
                        }
                    }else{
                        return redirect()->to(base_url()."inicio/finalizar_relacion/".$post["idFinalRel"])->with("error","Relacion finalizada con exito.<br>No se pudo modificar la fecha de defuncion de la mascota.");
                    }
                }else{
                    return redirect()->to(base_url()."inicio/finalizar_relacion/".$post["idFinalRel"])->with("error","Relacion finalizada con exito.<br>No se pudo modificar la fecha de defuncion de la mascota.");
                }
            }
            return redirect()->to(base_url()."inicio")->with("success","Relacion finalizada con exito");
        }
    }

    public function newRelacionMascotaAmo($idMascota=null){
        helper(['form', 'SpanishErrors_helper']);
        $post=$this->request->getPost();
        if(empty($post)){
            try{
                $amo=new AmoModel();
                $amos=$amo->getAllAmosList();
            }catch(Error $e){
                return redirect()->to(base_url()."inicio")->with("error","Ocurrio un error al intentar crear la relacion");
            }
            $data=[
                "AmosDisponibles"=>$amos,
                "idMascotaNewRelacion"=>$idMascota
            ];
            return view("newRelacionView",$data);
        }else{
            $rules=[
                "fechaNewRel"=>"required|valid_date",
                "amoNewRelacion"=>"required"
            ];
            $validacion=service("validation");
            $validacion->setRules($rules,spanishErrorMessages($rules));
            if(!$validacion->withRequest($this->request)->run()){
                return redirect()->to(base_url()."inicio/new_relacion_mascota_amo/".$post["idNewRel"])->withInput()->with('errors', $validacion->getErrors());
            }
            $sqlIn=[
                "fechaInicioAmoMascota"=>$post["fechaNewRel"],
                "idAmo"=>$post["amoNewRelacion"],
                "idMascota"=>$post["idNewRel"]
            ];
            try{
                $relAmoMasc=new AmoMascotaModel();
                if(!$relAmoMasc->insert($sqlIn)){
                    $relAmoMasc=null;
                    return redirect()->to(base_url()."inicio/new_relacion_mascota_amo/".$post["idNewRel"])->with("error","Ocurrio un error al intentar crear la relacion");
                }
                return redirect()->to(base_url()."inicio")->with("success","Relacion creada con exito");
            }catch(Error $e){
                return redirect()->to(base_url()."inicio/new_relacion_mascota_amo/".$post["idNewRel"])->with("error","Ocurrio un error al intentar crear la relacion");
            }
        }
    }    
    public function newRelacionAmoMascota($idAmo=null){
        helper(['form', 'SpanishErrors_helper']);
        $post=$this->request->getPost();
        if(empty($post)){
            try{
                $mascota=new MascotaModel();
                $mascotas=$mascota->getAllMascotasVivasList();
            }catch(Error $e){
                return redirect()->to(base_url()."inicio")->with("error","Ocurrio un error al intentar crear la relacion");
            }
            $data=[
                "MascotasDisponibles"=>$mascotas,
                "idAmoNewRelacion"=>$idAmo
            ];
            return view("newRelacionView",$data);
        }else{
            $rules=[
                "fechaNewRel"=>"required|valid_date",
                "mascotaNewRelacion"=>"required"
            ];
            $validacion=service("validation");
            $validacion->setRules($rules,spanishErrorMessages($rules));
            if(!$validacion->withRequest($this->request)->run()){
                return redirect()->to(base_url()."inicio/new_relacion_amo_mascota/".$post["idNewRel"])->withInput()->with('errors', $validacion->getErrors());
            }
            $sqlIn=[
                "fechaInicioAmoMascota"=>$post["fechaNewRel"],
                "idAmo"=>$post["idNewRel"],
                "idMascota"=>$post["mascotaNewRelacion"]
            ];
            try{
                $relAmoMasc=new AmoMascotaModel();
                if(!$relAmoMasc->insert($sqlIn)){
                    $relAmoMasc=null;
                    return redirect()->to(base_url()."inicio/new_relacion_amo_mascota/".$post["idNewRel"])->with("error","Ocurrio un error al intentar crear la relacion");
                }
                return redirect()->to(base_url()."inicio")->with("success","Relacion creada con exito");
            }catch(Error $e){
                return redirect()->to(base_url()."inicio/new_relacion_amo_mascota/".$post["idNewRel"])->with("error","Ocurrio un error al intentar crear la relacion");
            }
        }
    }    
}
