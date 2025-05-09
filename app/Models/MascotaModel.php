<?php
namespace App\Models;
use CodeIgniter\Model;

class MascotaModel extends Model{
    protected $table = "Mascotas";
    protected $primaryKey = 'idMascota';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nombreMascota', 'especieMascota','razaMascota','edadMascota','fechaAltaMascota','fechaDefuncionMascota'];
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    public function getAllMascotasVivas(){
        return $this->select("nombreMascota, edadMascota, especieMascota, razaMascota, fechaAltaMascota, fechaDefuncionMascota")
        ->join("AmosMascotas","AmosMascotas.idMascota=Mascotas.idMascota","left")
        ->where("Mascotas.fechaDefuncionMascota IS NULL AND (AmosMascotas.idMascota IS NULL OR AmosMascotas.fechaFinAmoMascota IS NOT NULL)")
        ->findAll();
    }

    public function getAllMascotasList(){
        return $this->select("idMascota, nombreMascota")->findAll();
    }
}
