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
        return $this->select("mascotas.idMascota AS id,nombreMascota, edadMascota, especieMascota, razaMascota, fechaAltaMascota")
        ->join("AmosMascotas","AmosMascotas.idMascota=Mascotas.idMascota","left")
        ->where("(Mascotas.fechaDefuncionMascota IS NULL AND AmosMascotas.idMascota IS NULL) OR (Mascotas.fechaDefuncionMascota IS NULL AND AmosMascotas.fechaFinAmoMascota IS NOT NULL)")
        ->groupBy("mascotas.idMascota")
        ->findAll();
    }
    public function getAllMascotas(){
        return $this->select("mascotas.idMascota AS id, nombreMascota, edadMascota, especieMascota, razaMascota, fechaAltaMascota, fechaDefuncionMascota")
        ->findAll();
    }

    public function getAllMascotasVivasList(){
        return $this->select("mascotas.idMascota, mascotas.nombreMascota")
        ->join("AmosMascotas","AmosMascotas.idMascota=Mascotas.idMascota","left")
        ->where("(Mascotas.fechaDefuncionMascota IS NULL AND AmosMascotas.idMascota IS NULL) OR (Mascotas.fechaDefuncionMascota IS NULL AND AmosMascotas.fechaFinAmoMascota IS NOT NULL)")
        ->groupBy("mascotas.idMascota")
        ->findAll();
    }
    public function getAllMascotasList(){
        return $this->select("idMascota, nombreMascota")->findAll();
    }

    public function getMascota($id){
        return $this->select("idMascota")->find($id);
    }
    public function getAllIdMascotasVivas(){
        return $this->select("mascotas.idMascota AS id")
        ->join("AmosMascotas","AmosMascotas.idMascota=Mascotas.idMascota","left")
        ->where("(Mascotas.fechaDefuncionMascota IS NULL AND AmosMascotas.idMascota IS NULL) OR (Mascotas.fechaDefuncionMascota IS NULL AND AmosMascotas.fechaFinAmoMascota IS NOT NULL)")
        ->groupBy("mascotas.idMascota")
        ->findAll();
    }
    public function getAllIdMascotas(){
        return $this->select("mascotas.idMascota AS id")
        ->findAll();
    }
}
