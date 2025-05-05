<?php
namespace App\Models;
use CodeIgniter\Model;

class AmoModel extends Model{
    protected $table = "Amos";
    protected $primaryKey = 'idAmo';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nombreAmo', 'apellidoAmo','direccionAmo','telefonoAmo','fechaAltaAmo'];
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
}
