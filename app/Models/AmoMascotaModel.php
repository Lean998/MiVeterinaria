<?php
namespace App\Models;
use CodeIgniter\Model;

class amoMascotaModel extends Model{
    protected $table = "AmosMascotas";
    protected $primaryKey = "idAmoMascota";
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['idAmo', 'idMascota','fechaInicioAmoMascota','fechaFinAmoMascota','motivoFin','deleted_at'];
    protected bool $updateOnlyChanged = true;
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    public function getAllAmoMascotas($amoId){
        return $this->select("mascotas.nombreMascota, mascotas.edadMascota, mascotas.especieMascota, mascotas.razaMascota, amosmascotas.fechaInicioAmoMascota, amosmascotas.fechaFinAmoMascota, amosmascotas.motivoFin")
        ->join("mascotas","mascotas.idMascota = amosmascotas.idMascota AND mascotas.deleted_at IS NULL")
        ->where('amosmascotas.idAmo='.$amoId." AND AmosMascotas.deleted_at IS NULL")
        ->findAll();
    }
    public function getAllIdAmoMascotas($amoId){
        return $this->select("idMascota AS id")->where("idAmo=".$amoId." AND AmosMascotas.deleted_at IS NULL")->findAll();
    }
    public function getAllIdAmoMascotasRel($amoId){
        return $this->select("idAmoMascota AS id")->where("idAmo=".$amoId." AND AmosMascotas.deleted_at IS NULL")->findAll();
    }
    public function getAllMascotaAmos($mascotaId){
        return $this->select("amos.nombreAmo, amos.apellidoAmo, amos.telefonoAmo, amosmascotas.fechaInicioAmoMascota, amosmascotas.fechaFinAmoMascota, amosmascotas.motivoFin")
        ->join("amos","amos.idAmo = amosmascotas.idAmo AND amos.deleted_at IS NULL")
        ->where('amosmascotas.idMascota='. $mascotaId." AND AmosMascotas.deleted_at IS NULL")
        ->orderBy("amosmascotas.fechaInicioAmoMascota","desc")
        ->findAll();
    }

    public function getAllIdsRelacionTodasMascotas(){
        $db = \Config\Database::connect();
        $sql='  SELECT CASE WHEN amoActual.id IS NULL THEN "" ELSE amoActual.id END AS id
                FROM mascotas
                LEFT JOIN (
                        SELECT idMascota, idAmoMascota AS id
                        FROM amosmascotas
                        WHERE fechaFinAmoMascota IS NULL AND amosmascotas.deleted_at IS NULL
                    ) AS amoActual ON amoActual.idMascota = mascotas.idMascota
                WHERE mascotas.deleted_at IS NULL
                ORDER BY mascotas.idMascota DESC
                                    ';
        $query   = $db->query($sql);
        $idRelMascotas = $query->getResultArray();
        $db->close();
        return $idRelMascotas;
    }

    public function getAllIdMascotaAmos($mascotaId){
        return $this->select("idAmo AS id")->where("idMascota=".$mascotaId." AND AmosMascotas.deleted_at IS NULL")->findAll();
    }
    public function getAllIdMascotaAmosRel($mascotaId){
        return $this->select("idAmoMascota AS id")->where("idMascota=".$mascotaId." AND AmosMascotas.deleted_at IS NULL")->findAll();
    }
    public function eliminarRelacionesMascota($idMascota){
        $db = \Config\Database::connect();
        $hoy=\CodeIgniter\I18n\Time::now()->format('Y-m-d H:i:s');
        $sql='  UPDATE amosmascotas
                SET amosmascotas.deleted_at = "'.$hoy.'", amosmascotas.fechaFinAmoMascota = "'.$hoy.'"
                WHERE amosmascotas.idMascota='.$idMascota.'
                        AND amosmascotas.fechaFinAmoMascota IS NULL
                        AND amosmascotas.deleted_at IS NULL
                ';
        $res   = $db->query($sql);
        $db->close();
        return $res;
    }
    public function eliminarRelacionesAmo($idAmo){
        $db = \Config\Database::connect();
        $hoy=\CodeIgniter\I18n\Time::now()->format('Y-m-d H:i:s');
        $sql='  UPDATE amosmascotas
                SET amosmascotas.deleted_at = "'.$hoy.'", amosmascotas.fechaFinAmoMascota = "'.$hoy.'"
                WHERE amosmascotas.idAmo='.$idAmo.'
                        AND amosmascotas.fechaFinAmoMascota IS NULL
                        AND amosmascotas.deleted_at IS NULL
                ';
        $res   = $db->query($sql);
        $db->close();
        return $res;
    }
}
