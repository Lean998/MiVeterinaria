<?php
namespace App\Models;
use CodeIgniter\Model;

class amoMascotaModel extends Model{
    protected $table = "AmosMascotas";
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['idAmo', 'idMascota','fechaInicioAmoMascota','fechaFinAmoMascota'];
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    public function getAllAmoMascotas($amoId){
        return $this->select("mascotas.nombreMascota, mascotas.edadMascota, mascotas.especieMascota, mascotas.razaMascota, amosmascotas.fechaInicioAmoMascota, amosmascotas.fechaFinAmoMascota")
        ->join("mascotas","mascotas.idMascota = amosmascotas.idMascota")
        ->where('amosmascotas.idAmo', $amoId)
        ->findAll();
    }
    public function getAllIdAmoMascotas($amoId){
        return $this->select("idMascota AS id")->where("idAmo=".$amoId)->findAll();
    }
    public function getAllMascotaAmos($mascotaId){
        return $this->select("amos.nombreAmo, amos.apellidoAmo, amos.telefonoAmo, amosmascotas.fechaInicioAmoMascota, amosmascotas.fechaFinAmoMascota")
        ->join("amos","amos.idAmo = amosmascotas.idAmo")
        ->where('amosmascotas.idMascota', $mascotaId)
        ->findAll();
    }
    public function getAllIdMascotaAmos($mascotaId){
        return $this->select("idAmo AS id")->where("idMascota=".$mascotaId)->findAll();
    }
}
