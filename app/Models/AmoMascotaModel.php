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
        return $this->select("mascotas.nombreMascota, mascotas.edadMascota, mascotas.especieMascota, mascotas.razaMascota, amosmascotas.fechaInicioAmoMascota, amosmascotas.fechaFinamoMascota")
        ->join("mascotas","mascotas.idMascota = amosmascotas.idMascota")
        ->where('amosmascotas.idAmo', $amoId)
        ->findAll();
    }
    public function getAllMascotaAmos($mascotaId){
        return $this->select("amos.nombreAmo, amos.apellidoAmo, amos.telefonoAmo, amos.fechaAltaAmo")
        ->join("amos","amos.idAmo = amosmascotas.idAmo")
        ->where('amosmascotas.idMascota', $mascotaId)
        ->findAll();
    }
}
