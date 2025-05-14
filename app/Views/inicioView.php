<?php
    if(isset($tipoMetodo)){
    switch($tipoMetodo){
        case "Mascotas": if(isset($datos["mascotasVivas"])&&isset($datos["idMascotas"])){
                            $data=mascotas($datos["mascotasVivas"],$datos["idMascotas"]);
                            }
                            break;
        case "TodasMascotas": if(isset($datos["todasMascotas"])&&isset($datos["idMascotas"])){
                            $data=todasMascotas($datos["todasMascotas"],$datos["idMascotas"]);
                            }
                            break;
        case "Amos": if(isset($datos["amos"])&&isset($datos["idAmos"])){
                        $data=amos($datos["amos"],$datos["idAmos"]);
                        }
                        break;
        case "Veterinarios": if(isset($datos["veterinarios"])&&isset($datos["idVeterinarios"])){
                                $data=veterinarios($datos["veterinarios"],$datos["idVeterinarios"]);
                                }
                                break;
        case "MascotaAmos": if(isset($datos["mascota"])&&isset($datos["amos"])){
                                $data=mascotaAmos($datos["mascota"],$datos["amos"],$datos["idAmos"],$datos["idRel"]);
                            }elseif(isset($datos["mascota"])&&!isset($datos["amos"])){
                                $data=mascotaAmos($datos["mascota"]);
                            }elseif(!isset($datos["mascota"])){
                                $data=mascotaAmos();
                            }
                            break;
        case "AmoMascotas": if(isset($datos["amo"])&&isset($datos["mascotas"])){
                                $data=amoMascotas($datos["amo"],$datos["mascotas"],$datos["idMascotas"],$datos["idRel"]);
                            }elseif(isset($datos["amo"])&&!isset($datos["mascotas"])){
                                $data=amoMascotas($datos["amo"]);
                            }elseif(!isset($datos["amo"])){
                                $data=amoMascotas();
                            }
                            break;
    }}

    function generarTabla($arreglo,$tabla,$nColumnas,&$validNew,$ids=null,$idsRel=null){
        $i=0;
        if($ids!=null){
            $tabla.="<th style='width: 4.3rem;'></th>";
        }
        $tabla.="</thead>
                <tbody>";
        foreach($arreglo as $valor){
            if(isset($valor["id"]))$j=0;
            $tabla .= '<tr>';
            foreach($valor as $item){
                if(isset($j))if($j==0){$j++;continue;}
                $tabla.='<td>'. $item.' </td>';
            }
            if($ids!=null)if(!empty($ids)){
                if(isset($valor["nombreMascota"])){$metodoModificar=true;$metodoEliminar="mascota/eliminar_mascota";$metodoDifunto="mascota/mascota_difunta";}
                if(isset($valor["nombreAmo"])){$metodoModificar=true;$metodoEliminar="amo/eliminar_amo";}
                if(isset($valor["fechaDefuncionMascota"])){if($valor["fechaDefuncionMascota"]!=""){$difunto=true;}}
                if(isset($valor["nombreVeterinario"])){$metodoModificar=true;$metodoEliminar="veterinario/eliminar_veterinario";}
                if($idsRel!=null)if(!empty($idsRel))if(isset($valor["fechaFinAmoMascota"]))if($valor["fechaFinAmoMascota"]==""){$metodoFinalizar="inicio/finalizar_relacion";$validNew=false;}
                $tabla.='<td><div class="dropdown options">
                            <button class="btn dropdown-toggle dark btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                            <ul class="dropdown-menu dark">';
                if(isset($valor["nombreMascota"]) && isset($valor["fechaAltaMascota"]) && !isset($difunto))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url("mascota_amo/new_relacion_mascota_amo/".$ids[$i]["id"]).'">Nueva Relacion</a>
                                </li>';
                if(isset($valor["nombreAmo"]) && isset($valor["fechaAltaAmo"]))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url("amo_mascota/new_relacion_amo_mascota/".$ids[$i]["id"]).'">Nueva Relacion</a>
                                </li>';
                if(isset($metodoModificar) && !isset($valor["fechaFinAmoMascota"]) && !isset($difunto)){
                                    $tipoEntidad = '';
                                    if (isset($valor["nombreMascota"])) $tipoEntidad = 'mascota';
                                    elseif (isset($valor["nombreAmo"])) $tipoEntidad = 'amo';
                                    elseif (isset($valor["nombreVeterinario"])) $tipoEntidad = 'veterinario';
                                    
                                    if($tipoEntidad !== ''){
                                        $tabla.='<li>
                                         <a class="dropdown-item text-reset text-decoration-none" href="'.base_url("inicio/modificar/".$tipoEntidad."/".$ids[$i]["id"]).'">Modificar</a>
                                        </li>';
                                    }
                }
                if(isset($metodoFinalizar))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url($metodoFinalizar."/".$idsRel[$i]["id"]).'">Finalizar</a>
                                </li>';
                if(isset($metodoDifunto) && !isset($difunto))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url($metodoDifunto."/".$ids[$i]["id"]).'">Dar de baja</a>
                                </li>';
                if(isset($metodoEliminar))$tabla.='<li>
                                    <a class="dropdown-item text-reset text-decoration-none" href="'.base_url($metodoEliminar."/".$ids[$i]["id"]).'">Eliminar</a>
                                </li>';
                $tabla.='</ul>
                         </div></td>';
            }
            $tabla.='</tr>';
            $i++;
        }
        if(sizeof($arreglo)<10){
            for($i=0;$i<(10-sizeof($arreglo));$i++){
                $tabla.='<tr>';
                for($j=0;$j<$nColumnas;$j++){
                    $tabla.="<td> </td>";
                }
                if($ids!=null)$tabla.="<td> </td>";
                $tabla.='</tr>';
            }
        }
        $tabla .= '</tbody> </table>';
        return $tabla;
    }

    function mascotas($mascotasVivas,$idMascotas){
        $validNew=true;        
        $tabla = '
        <table>
            <thead>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th style="width: 9rem;">Fecha Alta</th>
        ';
        if(isset($mascotasVivas) && isset($idMascotas)){
            for($i=0; $i<sizeof($mascotasVivas); $i++){
                $mascotasVivas[$i]["fechaAltaMascota"]=substr($mascotasVivas[$i]["fechaAltaMascota"],0,-9);
            }
            $tabla=generarTabla($mascotasVivas,$tabla,5,$validNew,$idMascotas);
        }else{
            $tabla=generarTabla([],$tabla,5,$validNew);
        }
        $data['table']=$tabla;
        if(!$validNew)$data["invalidNew"]=true;
        return $data;
    }

    function todasMascotas($mascotas,$idMascotas){
        $validNew=true;
        $tabla = '
        <table>
            <thead>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th style="width: 9rem;">Fecha Alta</th>
                    <th style="width: 10rem;">Fecha Defuncion</th>
        ';
        if(isset($mascotas) && isset($idMascotas)){
            for($i=0; $i<sizeof($mascotas); $i++){
                $mascotas[$i]["fechaAltaMascota"]=substr($mascotas[$i]["fechaAltaMascota"],0,-9);
                if(!isset($mascotas[$i]["fechaDefuncionMascota"])){
                    $mascotas[$i]["fechaDefuncionMascota"]="";
                }else{
                    $mascotas[$i]["fechaDefuncionMascota"]=substr($mascotas[$i]["fechaDefuncionMascota"],0,-9);
                }
            }
            $tabla=generarTabla($mascotas,$tabla,6,$validNew,$idMascotas);
        }else{
            $tabla=generarTabla([],$tabla,6,$validNew);
        }
        $data['table'] = $tabla;
        
        return $data;
    }

    function amoMascotas($amo=null,$mascotas=null,$idMascotas=null,$idRel=null) {
        $validNew=true;
        if(!isset($amo)){
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th style="width: 10rem;">Fecha Inicio Relacion</th>
                        <th style="width: 10rem;">Fecha Fin Relacion</th>
            ';
            $tabla=generarTabla([],$tabla,6,$validNew);
            $data=[
                "table"=>$tabla
            ];
            return $data;
        }
        else{
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th style="width: 10rem;">Fecha Inicio Relacion</th>
                        <th style="width: 10rem;">Fecha Fin Relacion</th>
            ';
            if(isset($mascotas) && isset($idMascotas) && isset($idRel)){
                for($i=0; $i<sizeof($mascotas); $i++){
                    $mascotas[$i]["fechaInicioAmoMascota"]=substr($mascotas[$i]["fechaInicioAmoMascota"],0,-9);
                    if($mascotas[$i]["fechaFinAmoMascota"]!=null){
                        $mascotas[$i]["fechaFinAmoMascota"]=substr($mascotas[$i]["fechaFinAmoMascota"],0,-9);
                    }else{
                        $mascotas[$i]["fechaFinAmoMascota"]="";
                    }
                }
                $tabla=generarTabla($mascotas,$tabla,6,$validNew,$idMascotas,$idRel);
            }else{
                $tabla=generarTabla([],$tabla,6,$validNew);
            }
            $data['table'] = $tabla;
            $data['newRelAmoMasc']='<a class="text-reset text-decoration-none" href="'.base_url("amo_mascotas/new_relacion_amo_mascota/".$amo).'">Agregar Relacion Amo-Mascota</a>';
            return $data;
        }
    }

    function mascotaAmos($mascota=null,$amos=null,$idAmos=null,$idRel=null) {
        $validNew=true;
        if(!isset($mascota)){
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Telefono</th>
                        <th style="width: 10rem;">Fecha Inicio Relacion</th>
                        <th style="width: 10rem;">Fecha Fin Relacion</th>
            ';
            $tabla=generarTabla([],$tabla,5,$validNew);
            $data=[
                "table"=>$tabla
            ];
            return $data;
        }
        else{
            $tabla = '
            <table>
                <thead>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Telefono</th>
                        <th style="width: 10rem;">Fecha Inicio Relacion</th>
                        <th style="width: 10rem;">Fecha Fin Relacion</th>
            ';
            if(isset($amos) && isset($idAmos)){
                for($i=0; $i<sizeof($amos); $i++){
                    $amos[$i]["fechaInicioAmoMascota"]=substr($amos[$i]["fechaInicioAmoMascota"],0,-9);
                    if($amos[$i]["fechaFinAmoMascota"]!=null){
                        $amos[$i]["fechaFinAmoMascota"]=substr($amos[$i]["fechaFinAmoMascota"],0,-9);
                    }else{
                        $amos[$i]["fechaFinAmoMascota"]="";
                    }
                }
                $tabla=generarTabla($amos,$tabla,5,$validNew,$idAmos,$idRel);
            }else{
                $tabla=generarTabla([],$tabla,5,$validNew);
            }
            $data['table'] = $tabla;
            if(!$validNew)$data["invalidNew"]=true;
            $data['newRelMascAmo']='<a class="text-reset text-decoration-none" href="'.base_url("mascota_amos/new_relacion_mascota_amo/".$mascota).'">Agregar Relacion Mascota-Amo</a>';
            return $data;
        }
    }

    function amos($amos,$idAmos){
        $validNew=true;
        $tabla = '
        <table>
            <thead>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Telefono</th>
                    <th style="width: 9rem;">Fecha Alta</th>
        ';
        if(isset($amos) && isset($idAmos)){
            for($i=0; $i<sizeof($amos); $i++){
                $amos[$i]["fechaAltaAmo"]=substr($amos[$i]["fechaAltaAmo"],0,-9);
            }
            $tabla=generarTabla($amos,$tabla,4,$validNew,$idAmos);
        }else{
            $tabla=generarTabla([],$tabla,4,$validNew);
        }
        if(!$validNew)$data["invalidNew"]=true;
        $data['table']=$tabla;
        return $data;
    }

    function veterinarios($veterinarios,$idVeterinados){
        $validNew=true;
        $tabla = '
        <table>
            <thead>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Especialidad</th>
                    <th>Telefono</th>
                    <th style="width: 9rem;">Fecha Ingreso</th>
                    <th style="width: 9rem;">Fecha Egreso</th>
        ';
        if(isset($veterinarios) && isset($idVeterinados)){
            for($i=0; $i<sizeof($veterinarios); $i++){
                $veterinarios[$i]["fechaIngresoVeterinario"]=substr($veterinarios[$i]["fechaIngresoVeterinario"],0,-9);
                if($veterinarios[$i]["fechaEgresoVeterinario"]!=null){
                    $veterinarios[$i]["fechaEgresoVeterinario"]=substr($veterinarios[$i]["fechaEgresoVeterinario"],0,-9);
                }
            }
            $tabla=generarTabla($veterinarios,$tabla,6,$validNew,$idVeterinados);
        }else{
            $tabla=generarTabla([],$tabla,6,$validNew);
        }
        if(!$validNew)$data["invalidNew"]=true;
        $data['table']=$tabla;
        return $data;
    }

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= substr(base_url(),0,-17) ?>Plantilla/Css/inicio.css">
    <title>Inicio</title>
</head>
<body>
    <header class="d-flex col-12 align-items-center justify-content-between">
        <div class="col-auto ms-3"><a href="<?= base_url()."inicio"?>"><img class="img-fluid" src="<?= substr(base_url(),0,-17)?>Plantilla/imgs/MenuLogo.jpg" alt="Logo"></a></div>
        <div class="col-10">
            <ul class="d-flex mb-0">
                <li class="dropdown-item">
                    <a class=" text-reset text-decoration-none" href="<?= base_url()."mascota/"?>">Mascotas</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url('amo/')?>">Amos</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url('veterinario/')?>">Veterinarios</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url()."amo_mascotas"?>">Amo_Mascotas</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url()."mascota_amos"?>">Mascota_Amos</a>
                </li>
            </ul>
        </div>
    </header>
    <div class="container py-3">
        <div id="mensaje-success" class="alert d-none" role="alert"></div>
    </div>
    <section class="d-flex col-12 flex-column align-items-center mb-3">
        <div class="col-10 d-flex flex-column align-items-center">
            <div class="d-flex align-items-end justify-content-center">
                <?php if(isset($mascota_amos_list)){?>
                    <form class="divListaSelect d-flex align-items-center" action="<?= base_url('amo_mascotas')?>" method="post" id="formularioAmoMascotas">
                        <select class="me-2" name="listaAmos" id="ListaAmos">
                            <option value="" selected disabled>Seleccione un amo de la lista </option>
                            <?php foreach($mascota_amos_list as $amo){?>
                                <?='<option name="amo" value=' .$amo['idAmo']. '>' .$amo['nombreAmo']. ' ' . $amo['apellidoAmo']. '</option>' ?>
                            <?php } ?>
                        </select>
                        <input type="submit" class="btn btn-sm btn-outline btn-primary" value="buscar" form="formularioAmoMascotas">
                    </form> 
                <?php }elseif(isset($amo_mascotas_list)){ ?>
                    <form class="divListaSelect d-flex align-items-center" action="<?= base_url('mascota_amos')?>" method="post" id="formularioMascotaAmos">
                        <select class="me-2" name="ListaMascotas" id="ListaMascotas">
                            <option value="" selected disabled>Seleccione una mascota de la lista </option>
                            <?php foreach($amo_mascotas_list as $mascota){?>
                                <?='<option name="mascota" value=' .$mascota['idMascota']. '>' . $mascota['nombreMascota']. '</option>' ?>
                            <?php } ?>
                        </select>
                        <input type="submit" class="btn btn-sm btn-outline btn-primary" value="buscar" form="formularioMascotaAmos">
                    </form>
                <?php }?>
            </div>
            <div class="col-12 d-flex flex-column align-items-center">
                <div class="col-12 tabla d-flex align-items-start justify-content-center">
                    <?php if(isset($data["table"])){echo "<div class='d-flex flex-column align-content-start'>";}
                    if(isset($tipoMetodo)){
                        echo "<div class='agregarButton col-12 d-flex aling-items-center justify-content-between'>";
                        switch($tipoMetodo){
                            case "Mascotas": echo "<button class='btn p-1 m-2' data-bs-toggle='modal' data-bs-target='#modalAgregarMascotas'>Agregar Mascota</button><button class='btn p-1 m-2'><a class='text-reset text-decoration-none' href='".base_url()."mascota/todas_mascotas'>Mostrar Todas</a></button>";break;
                            case "TodasMascotas": echo "<button class='btn p-1 m-2' data-bs-toggle='modal' data-bs-target='#modalAgregarMascotas'>Agregar Mascota</button><button class='btn p-1 m-2'><a class='text-reset text-decoration-none' href='".base_url()."mascota/todas_mascotas'>Mostrar Todas</a></button>";break;
                            case "Amos": echo "<button class='btn p-1 m-2' data-bs-toggle='modal' data-bs-target='#modalAgregarAmos'>Agregar Amo</button>";break;
                            case "Veterinarios": echo "<button class='btn p-1 m-2' data-bs-toggle='modal' data-bs-target='#modalAgregarVeterinarios'>Agregar Veterinario</button>";break; 
                            case "AmoMascotas": if(!isset($data["invalidNew"])){
                                                    echo "<button class='btn p-1 m-2'>".$data["newRelAmoMasc"]."</button>";
                                                }break; 
                            case "MascotaAmos": if(!isset($data["invalidNew"])){
                                                    echo "<button class='btn p-1 m-2'>".$data["newRelMascAmo"]."</button>";
                                                }
                        } 
                        echo "</div>";
                    }
                    if(isset($data["table"])){ 
                        echo $data["table"]."</div>";
                    }else{?> 
                        <div class="inicio col-12">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img class="img-fluid" src="<?= substr(base_url(),0,-17)?>Plantilla/imgs/InicioLogo.png" alt="Logo">
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </section>
    <!-- MODAL PARA EL ALTA DE MASCOTAS !-->
    <div class="modal fade" id="modalAgregarMascotas" tabindex="-1" aria-labelledby="modalAgregarMascotasLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarMascotasLabel">Agregar mascota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">    
                <form class="d-flex flex-wrap" action="<?=base_url()?>mascota/alta_mascotas " method="post">
                    <div class="row col-6">
                        <div>
                            <h5>Mascota</h5>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label for="nombreMascota" class="form-label">Nombre</label>
                                <input type="text"class="form-control <?= session('errors.nombreMascota') ? 'is-invalid' : '' ?>" value="<?= old('nombreMascota') ?>" id="nombreMascota" name="nombreMascota" >
                                <div class="invalid-feedback">
                                    <?= str_replace("nombreMascota","El nombre",session('errors.nombreMascota')) ?? '' ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="especieMascota" class="form-label">Especie</label>
                                <input type="text" class="form-control <?= session('errors.especieMascota') ? 'is-invalid' : '' ?>" value="<?= old('especieMascota') ?>" id="especieMascota" name="especieMascota" >
                                <div class="invalid-feedback">
                                    <?= str_replace("especieMascota","La especie",session('errors.especieMascota')) ?? '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label for="razaMascota" class="form-label">Raza</label>
                                <input type="text" class="form-control <?= session('errors.razaMascota') ? 'is-invalid' : '' ?>" value="<?= old('razaMascota') ?>" id="razaMascota" name="razaMascota" >
                                <div class="invalid-feedback">
                                    <?= str_replace("razaMascota","La raza",session('errors.razaMascota')) ?? '' ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="edadMascota" class="form-label">Edad</label>
                                <input type="number" class="form-control <?= session('errors.edadMascota') ? 'is-invalid' : '' ?>" value="<?= old('edadMascota') ?>" id="edadMascota" name="edadMascota" min="0" >
                                <div class="invalid-feedback">
                                    <?= str_replace("edadMascota","La edad",session('errors.edadMascota')) ?? '' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row col-6">
                        <div>
                            <label for="conAmo">Adjuntar nuevo amo?</label>
                            <input type="checkbox" name="conAmo" id="conAmo" value="nuevo" onchange="mostrarNuevoAmo(event)" <?php if(old('conAmo'))echo "checked";?>>
                        </div>
                        <div class="d-none">
                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <label for="conNombreAmo" class="form-label">Nombre</label>
                                    <input type="text"class="form-control <?= session('errors.conNombreAmo') ? 'is-invalid' : '' ?>" value="<?= old('conNombreAmo') ?>" id="conNombreAmo" name="conNombreAmo" >
                                    <div class="invalid-feedback">
                                        <?= str_replace("conNombreAmo","El nombre",session('errors.conNombreAmo')) ?? '' ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="conApellidoAmo" class="form-label">Apellido</label>
                                    <input type="text" class="form-control <?= session('errors.conApellidoAmo') ? 'is-invalid' : '' ?>" value="<?= old('conApellidoAmo') ?>" id="conApellidoAmo" name="conApellidoAmo" >
                                    <div class="invalid-feedback">
                                        <?= str_replace("conApellidoAmo","El apellido",session('errors.conApellidoAmo')) ?? '' ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <label for="conTelefonoAmo" class="form-label">Telefono</label>
                                    <input type="text" class="form-control <?= session('errors.conTelefonoAmo') ? 'is-invalid' : '' ?>" value="<?= old('conTelefonoAmo') ?>" id="conTelefonoAmo" name="conTelefonoAmo" >
                                    <div class="invalid-feedback">
                                        <?= str_replace("conTelefonoAmo","El telefono",session('errors.conTelefonoAmo')) ?? '' ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto d-flex flex-column align-items-center justify-content-around mb-4">
                                <label for="conFechaNewRelMA" class="form-label">Inicio de la relacion</label>
                                <input type="datetime-local" class="form-control <?= session('errors.conFechaNewRelMA') ? 'is-invalid' : '' ?>" value="<?= old('conFechaNewRelMA') ?>" id="conFechaNewRelMA" name="conFechaNewRelMA" max="<?php date_default_timezone_set("America/Argentina/Buenos_Aires"); echo date("Y-m-d H:i");?>" >
                                <div class="col-10 invalid-feedback">
                                    <?= str_replace("conFechaNewRelMA","La fecha de inicio de la relacion",session('errors.conFechaNewRelMA')) ?? '' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Guardar Mascota</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA EL ALTA DE AMOS !-->
    <div class="modal fade" id="modalAgregarAmos" tabindex="-1" aria-labelledby="modalAgregarAmosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarAmosLabel">Agregar Amo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">    
                <form class="d-flex flex-wrap" action="<?=base_url()?>amo/alta_amos " method="post">
                    <div class="row col-6">
                        <div>
                            <h5>Amo</h5>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label for="nombreAmo" class="form-label">Nombre</label>
                                <input type="text"class="form-control <?= session('errors.nombreAmo') ? 'is-invalid' : '' ?>" value="<?= old('nombreAmo') ?>" id="nombreAmo" name="nombreAmo" >
                                <div class="invalid-feedback">
                                    <?= str_replace("nombreAmo","El nombre",session('errors.nombreAmo')) ?? '' ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="apellidoAmo" class="form-label">Apellido</label>
                                <input type="text" class="form-control <?= session('errors.apellidoAmo') ? 'is-invalid' : '' ?>" value="<?= old('apellidoAmo') ?>" id="apellidoAmo" name="apellidoAmo" >
                                <div class="invalid-feedback">
                                    <?= str_replace("apellidoAmo","El apellido",session('errors.apellidoAmo')) ?? '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label for="telefonoAmo" class="form-label">Telefono</label>
                                <input type="text" class="form-control <?= session('errors.telefonoAmo') ? 'is-invalid' : '' ?>" value="<?= old('telefonoAmo') ?>" id="telefonoAmo" name="telefonoAmo" >
                                <div class="invalid-feedback">
                                    <?= str_replace("telefonoAmo","El telefono",session('errors.telefonoAmo')) ?? '' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row col-6">
                        <div>
                            <label for="conMascota">Adjuntar nueva mascota?</label>
                            <input type="checkbox" name="conMascota" id="conMascota" value="nuevo" onchange="mostrarNuevaMascota(event)" <?php if(old('conMascota'))echo "checked";?>>
                        </div>
                        <div class="d-none">
                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <label for="conNombreMascota" class="form-label">Nombre</label>
                                    <input type="text"class="form-control <?= session('errors.conNombreMascota') ? 'is-invalid' : '' ?>" value="<?= old('conNombreMascota') ?>" id="conNombreMascota" name="conNombreMascota" >
                                    <div class="invalid-feedback">
                                        <?= str_replace("conNombreMascota","El nombre",session('errors.conNombreMascota')) ?? '' ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="conEspecieMascota" class="form-label">Especie</label>
                                    <input type="text" class="form-control <?= session('errors.conEspecieMascota') ? 'is-invalid' : '' ?>" value="<?= old('conEspecieMascota') ?>" id="conEspecieMascota" name="conEspecieMascota" >
                                    <div class="invalid-feedback">
                                        <?= str_replace("conEspecieMascota","La especie",session('errors.conEspecieMascota')) ?? '' ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <label for="conRazaMascota" class="form-label">Raza</label>
                                    <input type="text" class="form-control <?= session('errors.conRazaMascota') ? 'is-invalid' : '' ?>" value="<?= old('conRazaMascota') ?>" id="conRazaMascota" name="conRazaMascota" >
                                    <div class="invalid-feedback">
                                        <?= str_replace("conRazaMascota","La raza",session('errors.conRazaMascota')) ?? '' ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="conEdadMascota" class="form-label">Edad</label>
                                    <input type="number" class="form-control <?= session('errors.conEdadMascota') ? 'is-invalid' : '' ?>" value="<?= old('conEdadMascota') ?>" id="conEdadMascota" name="conEdadMascota" min="0" >
                                    <div class="invalid-feedback">
                                        <?= str_replace("conEdadMascota","La edad",session('errors.conEdadMascota')) ?? '' ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto d-flex flex-column align-items-center justify-content-around mb-4">
                                <label for="conFechaNewRelAM" class="form-label">Inicio de la relacion</label>
                                <input type="datetime-local" class="form-control <?= session('errors.conFechaNewRelAM') ? 'is-invalid' : '' ?>" value="<?= old('conFechaNewRelAM') ?>" id="conFechaNewRelAM" name="conFechaNewRelAM" max="<?php date_default_timezone_set("America/Argentina/Buenos_Aires"); echo date("Y-m-d H:i");?>" >
                                <div class="col-10 invalid-feedback">
                                    <?= str_replace("conFechaNewRelAM","La fecha de inicio de la relacion",session('errors.conFechaNewRelAM')) ?? '' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="d-grid d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Guardar Amo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA EL ALTA DE VETERINARIOS !-->
    <div class="modal fade" id="modalAgregarVeterinarios" tabindex="-1" aria-labelledby="modalAgregarVeterinariosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarVeterinariosLabel">Agregar Veterinario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">    
                <form action="<?=base_url()?>veterinario/alta_veterinarios" method="post">
                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label for="nombreVeterinario" class="form-label">Nombre</label>
                                <input type="text"class="form-control <?= session('errors.nombreVeterinario') ? 'is-invalid' : '' ?>" value="<?= old('nombreVeterinario') ?>" id="nombreVeterinario" name="nombreVeterinario" >
                                <div class="invalid-feedback">
                                    <?= str_replace("nombreVeterinario","El nombre",session('errors.nombreVeterinario')) ?? '' ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="apellidoVeterinario" class="form-label">Apellido</label>
                                <input type="text" class="form-control <?= session('errors.apellidoVeterinario') ? 'is-invalid' : '' ?>" value="<?= old('apellidoVeterinario') ?>" id="apellidoVeterinario" name="apellidoVeterinario" >
                                <div class="invalid-feedback">
                                    <?= str_replace("apellidoVeterinario","El apellido",session('errors.apellidoVeterinario')) ?? '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label for="especialidadVeterinario" class="form-label">Especialidad</label>
                                <input type="text" class="form-control <?= session('errors.especialidadVeterinario') ? 'is-invalid' : '' ?>" value="<?= old('especialidadVeterinario') ?>" id="especialidadVeterinario" name="especialidadVeterinario" >
                                <div class="invalid-feedback">
                                    <?= str_replace("especialidadVeterinario","La especialidad",session('errors.especialidadVeterinario')) ?? '' ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="telefonoVeterinario" class="form-label">Telefono</label>
                                <input type="text" class="form-control <?= session('errors.telefonoVeterinario') ? 'is-invalid' : '' ?>" value="<?= old('telefonoVeterinario') ?>" id="telefonoVeterinario" name="telefonoVeterinario" >
                                <div class="invalid-feedback">
                                    <?= str_replace("telefonoVeterinario","El telefono",session('errors.telefonoVeterinario')) ?? '' ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Guardar Veterinario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
<script src="<?= substr(base_url(),0,-17) ?>Plantilla/scripts/mensajes.js"></script>
<script>
        <?php if (session('success')): ?>
            mostrarMensaje('mensaje-success', <?= json_encode(session('success')) ?>, 'success');
        <?php elseif(session('error')): ?>
            mostrarMensaje('mensaje-success', <?= json_encode(session('error')) ?>, 'danger');
        <?php endif ?>
        window.addEventListener("load",()=>{
            var conAmo=document.querySelector("#conAmo");
            if(conAmo.checked){
                conAmo.parentElement.nextElementSibling.classList.remove("d-none");
            }
            var conAmo=document.querySelector("#conMascota");
            if(conAmo.checked){
                conAmo.parentElement.nextElementSibling.classList.remove("d-none");
            }
        });

        function mostrarNuevoAmo(e){
            if(e.target.parentElement.nextElementSibling.classList.length>0){
                e.target.parentElement.nextElementSibling.classList.remove("d-none");
            }else{
                e.target.parentElement.nextElementSibling.classList.add("d-none");
            }
            console.log(e.target.nextElementSibling.classList);
        }
        function mostrarNuevaMascota(e){
            if(e.target.parentElement.nextElementSibling.classList.length>0){
                e.target.parentElement.nextElementSibling.classList.remove("d-none");
            }else{
                e.target.parentElement.nextElementSibling.classList.add("d-none");
            }
            console.log(e.target.nextElementSibling.classList);
        }
</script>
</html>
