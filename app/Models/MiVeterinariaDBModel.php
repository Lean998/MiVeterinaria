<?php
namespace App\Models;
use CodeIgniter\Model;

class MiVeterinariaDBModel extends Model{
    private $forge;
    public function __construct(){
        $res=1;
        try{
            $this->forge = \Config\Database::forge();
        }catch(\Exception $e1){
            $res=$e1->getCode();
            try{
                $this->forge = \Config\Database::forge('aux');
            }
            catch(\Exception $e2){
                echo $e2->getMessage();
                exit();
            }
        }
        if(!$res){
            $res=$this->forge->createDatabase('MiVeterinaria', true);
        }
        if($res){
            $this->forge = \Config\Database::forge();
            $this->initMascotaModel();
            $this->initAmoModel();
            $this->initVeterinarioModel();
            $this->initAmoMascotaModel();
        }
    }
    private function initMascotaModel(){
        $fields=[
            "idMascota" => [
                "type" => "INT",
                "unasigned" => true,
                "auto_increment" => true
            ],
            "nombreMascota" => [
                "type" => "varchar",
                "constraint" => 255
            ],
            "especieMascota" => [
                "type" => "varchar",
                "constraint" => 255
            ],
            "razaMascota" => [
                "type" => "varchar",
                "constraint" => 255,
            ],
            "edadMascota" => [
                "type" => 'INT',
            ],
            "fechaAltaMascota" => [
                "type" => "datetime",
            ],
            "fechaDefuncionMascota" => [
                "type" => "datetime",
                "null" => true
            ]
        ];

        $this->forge->addPrimaryKey("idMascota");

        $attributes = [
            'engine' => 'InnoDB',
            'charset' => 'utf8mb4',
            'collate'=> 'utf8mb4_unicode_ci',
        ];
        $this->forge->addField($fields);
        $this->forge->createTable("Mascotas", true, $attributes);
    }
    private function initAmoModel(){
        $fields=[
            "idAmo" => [
                "type" => "INT",
                "unasigned" => true,
                "auto_increment" => true
            ],
            "nombreAmo" => [
                "type" => "varchar",
                "constraint" => 30
            ],
            "apellidoAmo" => [
                "type" => "varchar",
                "constraint" => 255
            ],
            "telefonoAmo"=>[
                "type" => "varchar",
                "constraint"=>13
            ],
            "fechaAltaAmo"=>[
                "type" => "datetime",
            ]
        ];

        $this->forge->addPrimaryKey("idAmo");
        $attributes = [
            'engine' => 'InnoDB',
            'charset' => 'utf8mb4',
            'collate'=> 'utf8mb4_unicode_ci',
        ];
        $this->forge->addField($fields);
        $this->forge->createTable("Amos", true, $attributes);
    }
    private function initVeterinarioModel(){
        $fields=[
            "idVeterinario" => [
                "type" => "int",
                "unasigned" => true,
                "auto_increment" => true
            ],
            "nombreVeterinaria" => [
                "type" => "varchar",
                "constraint" => 255
            ],
            "apellidoVeterinario" => [
                "type" => "varchar",
                "constraint" => 255
            ],
            "especialidadVeterinario" => [
                "type" => "varchar",
                "constraint" => 255
            ],
            "telefonoVeterinario" => [
                "type"=> "int",
                "unasigned" => true
            ],
            "fechaIngresoVeterinario" => [
                "type" => "datetime"
            ],
            "fechaEgresoVeterinario" => [
                "type" => "datetime",
                "null" => true
            ]
        ];

        $this->forge->addPrimaryKey("idVeterinario");
        $attributes = [
            'engine' => 'InnoDB',
            'charset' => 'utf8mb4',
            'collate'=> 'utf8mb4_unicode_ci',
        ];
        $this->forge->addField($fields);
        $this->forge->createTable("Veterinarios", true, $attributes);
    }
    private function initAmoMascotaModel(){
        $fields=[
            "idAmo" => [
                "type" => "INT",
                "null" => true,
                "unasigned" => true
            ],
            "idMascota" => [
                "type" => "INT",
                "null" => true,
                "unasigned" => true
            ],
            "fechaInicioAmoMascota" => [
                "type" => "datetime"
            ],
            "fechaFinAmoMascota"=>[
                "type" => "datetime",
                "null" => true
            ],
        ];

        $this->forge->addForeignKey("idAmo",
                                    "Amos",
                                    "idAmo",
                                    "cascade",
                                    "cascade",
                                    "fk_amoMascota_idAmo");
        $this->forge->addForeignKey("idMascota",
                                    "Mascotas",
                                    "idMascota",
                                    "cascade",
                                    "cascade",
                                    "fk_amoMascota_idMascota");
        $attributes = [
            'engine' => 'InnoDB',
            'charset' => 'utf8mb4',
            'collate'=> 'utf8mb4_unicode_ci',
        ];
        $this->forge->addField($fields);
        $this->forge->createTable("AmosMascotas", true, $attributes);
    }
}
