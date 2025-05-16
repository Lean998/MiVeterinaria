<?php
namespace App\Controllers;

use App\Libraries\SpanishErrorsLibrary;
use App\Models\VeterinarioModel;
use CodeIgniter\I18n\Time;
use Error;

class Veterinario extends BaseController {  
    public function index(){
        $veterinariosModel=new VeterinarioModel();
        try{
            $veterinarios=$veterinariosModel->getAllVeterinarios();
            $idVeterinarios=$veterinariosModel->select("idVeterinario AS id")->findAll();
        } catch(Error $e){
            return redirect()->to(base_url()."inicio")->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
        }
        $data['datos'] = [ 
            'veterinarios' => $veterinarios, 
            'idVeterinarios' => $idVeterinarios
        ];
        $data["tipoMetodo"]="Veterinarios";
        $data["cabeza"]=view("TrososView/headView");
        return view('inicioView', $data);
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
            return redirect()->to(base_url().'veterinario/')->withInput()->with('errors', $validacion->getErrors())->with('error', 'Datos invalidos, revise los datos ingresados!');
        }
        $veterinarioModel = new VeterinarioModel();
        $data = [
            'nombreVeterinario' => $this->request->getPost('nombreVeterinario'),
            'apellidoVeterinario' => $this->request->getPost('apellidoVeterinario'),
            'especialidadVeterinario' => $this->request->getPost('especialidadVeterinario'),
            'telefonoVeterinario' => $this->request->getPost('telefonoVeterinario'),
            'fechaIngresoVeterinario' => Time::now()->toDateTimeString()
        ];
        if($veterinarioModel->insert($data)){
            return redirect()->to(base_url().'veterinario/')->with('success', 'Veterinario registrado con exito!');
        } else{
            return redirect()->to(base_url().'veterinario/')->with('error', 'Ocurrio un error al registrar al veterinario, intente de nuevo mas tarde.');
        }
    }

    public function bajaVeterinario($id){
        try{
            if(isset($id)){
                $veterinarioModel=new VeterinarioModel();
                $veterinario=$veterinarioModel->getVeterinario($id);
                if(isset($veterinario)){if(!empty($veterinario)){
                    $dataVet=["id"=>$id,"tipo"=>"veterinario"];
                    $dataVet["cabeza"]=view("TrososView/headView");
                    return view("eliminarView",$dataVet);
                }else return redirect()->to(base_url()."veterinario/")->with("error","El veterinario no se encuentra en la tabla");
                }else return redirect()->to(base_url()."veterinario/")->with("error","Ocurrio un error al momento de intentar obtener al veterinario");
            }
            return redirect()->to(base_url()."veterinario/")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }catch(Error $e){
            return redirect()->to(base_url()."veterinario/")->with("error","Ocurrio un error inesperado. Estamos trabajando en ello");
        }
    }
}
