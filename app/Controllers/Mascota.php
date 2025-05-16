<?php 

namespace App\Controllers;

use App\Models\MascotaModel;
use App\Models\AmoModel;
use App\Models\AmoMascotaModel;
use CodeIgniter\I18n\Time;
use Error;

class Mascota extends BaseController
{ 
    public function index(){
        $mascotas=new MascotaModel();
        try{
            $mascotasVivas=$mascotas->getAllMascotasVivas();
            $idMascotas=$mascotas->getAllIdMascotasVivas();
        } catch(Error $e){
            return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
        }
        $data['datos']= [
            'mascotasVivas' => $mascotasVivas,
            'idMascotas' => $idMascotas,
        ];
        $data["tipoMetodo"]="Mascotas";
        $data["cabeza"]=view("TrososView/headView");
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
            return redirect()->to(base_url().'mascota/')->withInput()->with('errors', $validacion->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
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
                            return redirect()->to(base_url()."mascota/")->with("error","Mascota y Amo registrados con exito<br>La relacion entre Mascota y Amo no se pudo crear");
                        }
                        return redirect()->to(base_url()."mascota/")->with("success","Mascota, Amo y su relacion han sido registrados con exito");
                    }else{
                        return redirect()->to(base_url()."mascota/")->with("error","Mascota registrada con exito<br>El Amo y la relacion entre Mascota y Amo no se pudieron crear");
                    }
                }
                return redirect()->to(base_url().'mascota/')->with('success', 'Mascota registrada con exito!');
            } else{
                return redirect()->to(base_url().'mascota/')->with('error', 'Ocurrio un error al registrar la mascota, intente de nuevo mas tarde.');
            }
        }catch(Error $e){
            return redirect()->to(base_url().'inicio')->with('error', 'Ocurrio un error inesperado. Estamos trabajando en ello.');
        }
    }

    public function bajaMascota($id){
        try{
            if(isset($id)){
                $mascotaModel=new MascotaModel();
                $mascota=$mascotaModel->getMascota($id);
                if(isset($mascota)){if(!empty($mascota)){
                    $data=["id"=>$id,"tipo"=>"mascota"];
                    $data["cabeza"]=view("TrososView/headView");
                    return view("eliminarView",$data);
                }else return redirect()->to(base_url()."mascota/")->with("error","La mascota no se encuentra en la tabla");
                }else return redirect()->to(base_url()."mascota/")->with("error","Ocurrio un error al momento de intentar obtener la mascota");
            }
            return redirect()->to(base_url()."mascota/")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }catch(Error $e){
            return redirect()->to(base_url()."mascota/")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }
    }

    public function mascotaDifunta($id=null){
        try{
            if(isset($id)){
                $mascotaModel=new MascotaModel();
                $mascota=$mascotaModel->getMascota($id);
                if(isset($mascota)){if(!empty($mascota)){
                    $data=["id"=>$id];
                    $data["cabeza"]=view("TrososView/headView");
                    return view("difuntoView",$data);
                }else return redirect()->to(base_url()."mascota/")->with("error","La mascota no se encuentra en la tabla");
                }else return redirect()->to(base_url()."mascota/")->with("error","Ocurrio un error al momento de intentar obtener la mascota");
            }else{
                $post=$this->request->getPost(["id"]);
                if(isset($post["id"])){
                    helper(['form', 'SpanishErrors_helper']);
                    $rules=[
                        "fechaDefuncion"=>"required|valid_date"
                    ];
                    $validacion=service("validation");
                    $validacion->setRules($rules,spanishErrorMessages($rules));
                    if(!$validacion->withRequest($this->request)->run()){
                        return redirect()->to(base_url()."mascota/mascota_difunta/".$post["id"])->withInput()->with('errors', $validacion->getErrors());
                    }
                    $mascota=new MascotaModel();
                    if(!$mascota->update($post["id"],["fechaDefuncionMascota"=>$this->request->getPost("fechaDefuncion")])){
                        $mascota=null;
                        return redirect()->to(base_url()."mascota/")->with("error","No se pudo modificar la fecha de defuncion de la mascota.");
                    }
                    $mascota=null;
                    return redirect()->to(base_url()."mascota/")->with("success","La mascota ha sido declarada como difunta");
                }
            }
            return redirect()->to(base_url()."mascota/")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }catch(Error $e){
            return redirect()->to(base_url()."mascota/")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }
    }

    public function todasMascotas(){
        $mascotasModel=new MascotaModel();
        try{
            $mascotas=$mascotasModel->getAllMascotas();
            $idMascotas=$mascotasModel->getAllIdMascotas();
        } catch(Error $e){
            return redirect()->to(base_url()."mascota")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }
        $data['datos'] = [
            'todasMascotas' => $mascotas,
            'idMascotas' => $idMascotas,
        ];
        $data["tipoMetodo"]="TodasMascotas";
        $data["cabeza"]=view("TrososView/headView");
        return view('inicioView', $data);
    }
}
