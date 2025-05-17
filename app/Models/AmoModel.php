<?php
namespace App\Models;
use CodeIgniter\Model;

class AmoModel extends Model{
    protected $table = "Amos";
    protected $primaryKey = 'idAmo';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nombreAmo', 'apellidoAmo','direccionAmo','telefonoAmo','fechaAltaAmo','deleted_at'];
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    public function getAllAmosList(){
        return $this->select("idAmo, nombreAmo, apellidoAmo")->orderBy("nombreAmo","asc")->findAll();
    }
    public function getAllAmos(){
        return $this->select("nombreAmo, apellidoAmo, telefonoAmo, fechaAltaAmo")->orderBy("nombreAmo","asc")->findAll();
    }

    public function getAmo($id){
        return $this->select("idAmo")->find($id);
    }
}
