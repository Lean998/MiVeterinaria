<?php
namespace App\Models;
use CodeIgniter\Model;

class VeterinarioModel extends Model{
    protected $table = "Veterinarios";
    protected $primaryKey = 'idVeterinario';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nombreVeterinario', 'apellidoVeterinario','especialidadVeterinario','telefonoVeterinario','fechaIngresoVeterinario','fechaEgresoVeterinario','deleted_at'];
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    public function getAllVeterinarios(){
        return $this->select("nombreVeterinario, apellidoVeterinario, especialidadVeterinario, telefonoVeterinario, fechaIngresoVeterinario, fechaEgresoVeterinario")->findAll();
    }
    public function getVeterinario($id){
        return $this->select("idVeterinario")->find($id);
    }
}
