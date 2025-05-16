<?php 


namespace App\Controllers;

use App\Models\MascotaModel;
use App\Models\AmoModel;
use App\Models\AmoMascotaModel;
use Error;

class MascotaAmo extends BaseController
{       
    public function index() {
        $mascota=$this->request->getPost("listaMascotas");
        if(!isset($mascota)){
            $mascotaModel=new MascotaModel();
            try{
                $mascotas=$mascotaModel->getAllMascotasList();
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $data=[
                "amo_mascotas_list"=>$mascotas,
                "tipoMetodo"=>"MascotaAmos"
            ];
            $data["cabeza"]=view("TrososView/headView");
            return view("inicioView",$data);
        }
        else{
            $mascotaModel=new MascotaModel();
            try{
                $mascotas=$mascotaModel->getAllMascotasList();
                $fechaDefuncion=$mascotaModel->getFechaDefuncionMascota($mascota);
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $data["amo_mascotas_list"]=$mascotas;
            $amoMascotaModel = new AmoMascotaModel();
            try{
                $amos = $amoMascotaModel->getAllMascotaAmos($mascota);
                $idAmos= $amoMascotaModel->getAllIdMascotaAmos($mascota);
                $idRel=$amoMascotaModel->getAllIdMascotaAmosRel($mascota);
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $data['datos'] = [
                "fechaDefuncion"=>$fechaDefuncion,
                "mascota"=>$mascota,
                "amos"=>$amos,
                "idAmos"=>$idAmos,
                "idRel"=>$idRel
            ];
            $data["tipoMetodo"]="MascotaAmos";
            $data["cabeza"]=view("TrososView/headView");
            return view('inicioView', $data);
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
            $data["cabeza"]=view("TrososView/headView");
            return view("newRelacionView",$data);
        }else{
            $rules=[
                "fechaNewRel"=>"required|valid_date",
                "amoNewRelacion"=>"required"
            ];
            $validacion=service("validation");
            $validacion->setRules($rules,spanishErrorMessages($rules));
            if(!$validacion->withRequest($this->request)->run()){
                return redirect()->to(base_url()."mascota_amo/new_relacion_mascota_amo/".$post["idNewRel"])->withInput()->with('errors', $validacion->getErrors());
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
                    return redirect()->to(base_url()."mascota_amo/new_relacion_mascota_amo/".$post["idNewRel"])->with("error","Ocurrio un error al intentar crear la relacion");
                }
                return redirect()->to(base_url()."inicio")->with("success","Relacion creada con exito");
            }catch(Error $e){
                return redirect()->to(base_url()."mascota_amo/new_relacion_mascota_amo/".$post["idNewRel"])->with("error","Ocurrio un error al intentar crear la relacion");
            }
        }
    }       
}
?>