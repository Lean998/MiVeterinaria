<?php

namespace App\Controllers;
use App\Models\MascotaModel;
use App\Models\AmoModel;
use App\Models\AmoMascotaModel;
use App\Models\VeterinarioModel;
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
    
}
