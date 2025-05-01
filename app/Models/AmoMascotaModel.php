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

    public function getAllMascotas($amoId){
        return $this->where('idAmo', $amoId)
        ->where('fechaFinAmoMascota IS NULL')
        ->findAll();
    }
}
