<?php

namespace App\Controllers;
use App\Models\MascotaModel;
use App\Models\AmoModel;
use App\Models\AmoMascotaModel;
use Error;

class Inicio extends BaseController
{
    public function index(): string
    {
        return view('inicioView');
    }
    
    public function mascotas(){
        try{
            $mascotas=new MascotaModel();
            $mascotasVivas=$mascotas->join("AmosMascotas","AmosMascotas.idMascota=Mascotas.idMascota")->where("Mascotas.fechaDefuncionMascota IS NULL AND (AmosMascotas.idMascota IS NULL OR (AmosMascotas.idMascota IS NOT NULL AND AmosMascotas.fechaFinAmoMascota IS NOT NULL))")->find();
            if(!$mascotasVivas){
                return redirect()->to(base_url()."inicio")->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
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
            foreach($mascotasVivas as $mascota){
                $tabla .= 
                '<tr>
                    <td>'. $mascota['nombreMascota'].' </td>
                    <td>'. $mascota['edadMascota'].' </td>
                    <td>'. $mascota['especieMascota'].' </td>
                    <td>'. $mascota['razaMascota'].' </td>
                <tr>
                ';
            }
            $tabla .= '</tbody> </table>';
            $data['table']=$tabla;
            return view('inicioView', $data);
        }
        catch(Error $e){
            return redirect()->to(base_url()."inicio")->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
        }
    }

    public function amo_mascotas() {
    $amo = $this->request->getPost('amo');
    $amoMascotaModel = new AmoMascotaModel();
    try{
        $mascotas = $amoMascotaModel->getAllMascotas($amo);
    } catch(Error $e){
        return redirect()->back()->with("mensaje",["error"=>"","mensaje"=>"Ocurrio un error inesperado. Estamos trabajando en ello"]);
    }
    if(!$mascotas){
        $tabla = '<p>No hemos encontrado mascotas para el usuario seleccionado </p>';
        $data['table'] = $tabla;
        return view('inicioView', $data);
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
    foreach($mascotas as $mascota){
        $tabla .= 
        '<tr>
            <td>'. $mascota['nombreMascota'].' </td>
            <td>'. $mascota['edadMascota'].' </td>
            <td>'. $mascota['especieMascota'].' </td>
            <td>'. $mascota['razaMascota'].' </td>
            <td>'. $mascota['fechaInicio'].' </td>
            <td>'. $mascota['fechaFinal'].' </td>
        <tr>
        ';
    }

    $tabla .= '</tbody> </table>';
    $data = [
        'table' => $tabla,
    ];
    return view('inicioView', $data);
    }

    
}
