<?php
namespace App\Controllers;

use App\Models\AmoModel;
use App\Models\AmoMascotaModel;
use App\Models\MascotaModel;
use CodeIgniter\I18n\Time;
use Error;

class Amo extends BaseController {   
    public function index(){
        $amosModel=new AmoModel();
        try{
            $amos=$amosModel->getAllAmos();
            $idAmos=$amosModel->select("idAmo AS id")->findAll();
        } catch(Error $e){
            return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
        }
        $data['datos'] = [
            'amos' => $amos,
            'idAmos' => $idAmos,
        ];
        $data["tipoMetodo"]="Amos";
        $data["cabeza"]=view("TrososView/headView");
        return view('inicioView', $data);
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
            return redirect()->to(base_url().'amo/')->withInput()->with('errors', $validacion->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
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
                            return redirect()->to(base_url()."amo/")->with("error","Amo y Mascota registrados con exito<br>La relacion entre Amo y Mascota no se pudo crear");
                        }
                        return redirect()->to(base_url()."amo/")->with("success","Amo, Mascota y su relacion han sido registrados con exito");
                    }else{
                        return redirect()->to(base_url()."amo/")->with("error","Amo registrado con exito<br>La mascota y la relacion entre Amo y Mascota no se pudieron crear");
                    }
                }
                return redirect()->to(base_url().'amo/')->with('success', 'Amo registrado con exito!');
            } else{
                return redirect()->to(base_url().'amo/')->with('error', 'Ocurrio un error al registrar el amo, intente de nuevo mas tarde.');
            }
        }catch(Error $e){
            return redirect()->to(base_url().'inicio')->with('error', 'Ocurrio un error inesperado. Estamos trabajando en ello.');
        }
    }

    public function bajaAmo($id){
        try{
            if(isset($id)){
                $amoModel=new AmoModel();
                $amo=$amoModel->getAmo($id);
                if(isset($amo)){if(!empty($amo)){
                    $data=["id"=>$id,"tipo"=>"amo"];
                    $data["cabeza"]=view("TrososView/headView");
                    return view("eliminarView",$data);
                }else return redirect()->to(base_url()."amo/")->with("error","El amo no se encuentra en la tabla");
                }else return redirect()->to(base_url()."amo/")->with("error","Ocurrio un error al momento de intentar obtener al amo");
            }
            return redirect()->to(base_url()."amo/")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }catch(Error $e){
            return redirect()->to(base_url()."amo/")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }
    }
}
