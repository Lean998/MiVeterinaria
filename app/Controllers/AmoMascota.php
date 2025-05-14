<?php 


namespace App\Controllers;

use App\Models\MascotaModel;
use App\Models\AmoModel;
use App\Models\AmoMascotaModel;
use Error;

class AmoMascota extends BaseController
{
    public function index() {
        $amo=$this->request->getPost("listaAmos");
        if(!isset($amo)){
            $amoModel=new AmoModel();
            try{
                $amos=$amoModel->getAllAmosList();
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $data=[
                "mascota_amos_list"=>$amos,
            ];
            $data["tipoMetodo"]="AmoMascotas";
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
                $idRel=$amoMascotaModel->getAllIdAmoMascotasRel($amo);
            } catch(Error $e){
                return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
            }
            $data['datos'] = [
                "amo"=>$amo,
                "mascotas"=>$mascotas,
                "idMascotas"=>$idMascotas,
                "idRel"=>$idRel
            ];
            $data["tipoMetodo"]="AmoMascotas";
            return view('inicioView', $data);
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
                return redirect()->to(base_url()."amo_mascota/new_relacion_amo_mascota/".$post["idNewRel"])->withInput()->with('errors', $validacion->getErrors());
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
                    return redirect()->to(base_url()."amo_mascota/new_relacion_amo_mascota/".$post["idNewRel"])->with("error","Ocurrio un error al intentar crear la relacion");
                }
                return redirect()->to(base_url()."inicio")->with("success","Relacion creada con exito");
            }catch(Error $e){
                return redirect()->to(base_url()."amo_mascota/new_relacion_amo_mascota/".$post["idNewRel"])->with("error","Ocurrio un error al intentar crear la relacion");
            }
        }
    }    
}
