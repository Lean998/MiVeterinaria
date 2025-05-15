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
   
    public function eliminar(){
        try{
            $post=$this->request->getPost(["id"]);
            //var_dump($post);exit();
            if(isset($post["id"])){
                $tipo=$this->request->getPost(["tipo"]);
                if(isset($tipo["tipo"])){
                    if($tipo["tipo"]=="mascota"){
                        $relAM=new AmoMascotaModel();
                        if($relAM->eliminarRelacionesMascota($post["id"])){
                            $mascotaModel=new MascotaModel();
                            if($mascotaModel->delete($post["id"])){
                                return redirect()->to(base_url()."mascota/")->with("success","La mascota y sus relaciones han sido eliminadas con exito.");
                            }
                            return redirect()->to(base_url()."mascota/")->with("error","Las relaciones de la mascota han sido borradas<br>Ocurrio un error al intentar eliminar a la mascota");
                        }
                        return redirect()->to(base_url()."mascota/")->with("error","Ocurrio un error al intentar eliminar a la mascota");
                    }elseif($tipo["tipo"]=="amo"){
                        $relAM=new AmoMascotaModel();
                        if($relAM->eliminarRelacionesAmo($post["id"])){
                            $amoModel=new AmoModel();
                            if($amoModel->delete($post["id"])){
                                return redirect()->to(base_url()."amo/")->with("success","El amo y sus relaciones han sido eliminadas con exito.");
                            }
                            return redirect()->to(base_url()."amo/")->with("error","Las relaciones del amo han sido borradas<br>Ocurrio un error al intentar eliminar al amo");
                        }
                        return redirect()->to(base_url()."amo/")->with("error","Ocurrio un error al intentar eliminar al amo");
                    }elseif($tipo["tipo"]=="veterinario"){
                        $veterinarioModel=new VeterinarioModel();
                        if($veterinarioModel->delete($post["id"])){
                            return redirect()->to(base_url()."veterinario/")->with("success","El veterinario a sido eliminado con exito.");
                        }
                        return redirect()->to(base_url()."veterinario/")->with("error","Ocurrio un error al intentar eliminar al veterinario");
                    }
                }
                return redirect()->to(base_url()."inicio")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
            }
            return redirect()->to(base_url()."inicio")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }catch(Error $e){
            return redirect()->to(base_url()."inicio")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }
    }

    public function modificar($tipo = null, $id = null){
        if($tipo === null || $id === null){
            return redirect()->to(base_url('inicio'))->with('error',' Tipo o ID no especificado.');
        }

        $model = null;
        $dataEntidad = null;
        $redireccionError = base_url('inicio');

        try{
            switch($tipo){
                case 'mascota':
                    $model = new MascotaModel();
                    $dataEntidad = $model->find($id);
                    $redireccionError = base_url('mascota/');
                    break;
                case 'amo':
                    $model = new AmoModel();
                    $dataEntidad = $model->find($id);
                    $redireccionError = base_url('amo/');
                    break;  
                case 'veterinario':
                    $model = new VeterinarioModel();
                    $dataEntidad = $model->find($id);
                    $redireccionError = base_url('veterinario/');
                    break;
                default:    
                    return redirect()->to($redireccionError)->with('error', ucfirst($tipo) . ' no encontrado.');      
            }

            if (!$dataEntidad){
                return redirect()->to($redireccionError)->with('error', ucfirst($tipo) . ' no encontrado.');
            }
        }catch(Error $e){
            return redirect()->to($redireccionError)->with('error', ucfirst($tipo) . ' no encontrado.');
        }
        $data['tipo'] = $tipo;
        $data['entidad'] = $dataEntidad;

        return view('modificar_view', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $tipoPost = $this->request->getPost('tipo'); 
        $tipo = $tipoPost !== null ? trim($tipoPost) : null;

        if ($tipo === null || $id === null) {
            return redirect()->to(base_url('inicio'))->with('error', 'Datos de actualización incompletos.');
        }

        $model = null;
        $dataToUpdate = [];
        $redireccionError = base_url('inicio/modificar/' . $tipo . '/' . $id); 
        $idFieldName = 'id' . ucfirst($tipo);

        try {
            switch ($tipo) {
                case 'mascota':
                    $model = new MascotaModel();
                    $dataToUpdate = [
                        'nombreMascota' => $this->request->getPost('nombreMascota'),
                        'especieMascota' => $this->request->getPost('especieMascota'),
                        'razaMascota' => $this->request->getPost('razaMascota'),
                        'edadMascota' => $this->request->getPost('edadMascota'),
                        'fechaDefuncionMascota' => $this->request->getPost('fechaDefuncionMascota'),
                    ];
                    $redireccionExito = base_url('mascota');
                    break;
                case 'amo':
                    $model = new AmoModel();
                    $dataToUpdate = [
                        'nombreAmo'     => $this->request->getPost('nombreAmo'),
                        'apellidoAmo'   => $this->request->getPost('apellidoAmo'),
                        'telefonoAmo'   => $this->request->getPost('telefonoAmo'),
                    ];
                    $redireccionExito = base_url('amo');
                    break;
                case 'veterinario':
                    $model = new VeterinarioModel();
                    $dataToUpdate = [
                        'nombreVeterinario' => $this->request->getPost('nombreVeterinario'),
                        'apellidoVeterinario' => $this->request->getPost('apellidoVeterinario'),
                        'especialidadVeterinario' => $this->request->getPost('especialidadVeterinario'),
                        'telefonoVeterinario' => $this->request->getPost('telefonoVeterinario'),
                        'fechaEgresoVeterinario' => $this->request->getPost('fechaEgresoVeterinario'), 
                    ];
                    $redireccionExito = base_url('veterinario');
                    break;
                default:
                   
                    return redirect()->to(base_url('inicio'))->with('error', 'Tipo de entidad no válido para actualizar.');
            }

            if ($model === null) {
                 return redirect()->to(base_url('inicio'))->with('error', 'No se pudo determinar el modelo para la actualización.');
            }

            $updateResult = $model->update($id, $dataToUpdate);

            if ($updateResult) {
                return redirect()->to($redireccionExito)->with('success', ucfirst($tipo) . ' actualizado correctamente.');
            } else {
                return redirect()->to($redireccionError)->with('error', 'No se pudo actualizar el ' . $tipo . '. Verifique los datos e intente nuevamente.');
            }
        } catch (Error $e) {
            return redirect()->to($redireccionError)->with('error', 'Ocurrió un error al intentar actualizar el ' . $tipo . '. Estamos trabajando en ello.');
        }
    }
}

