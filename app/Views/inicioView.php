<!DOCTYPE html>
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
                    <a class=" text-reset text-decoration-none" href="<?= base_url()."inicio/mascotas"?>">Mascotas</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url('inicio/amos')?>">Amos</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url('inicio/veterinarios')?>">Veterinarios</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url()."inicio/amo_mascotas"?>">Amo_Mascotas</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url()."inicio/mascota_amos"?>">Mascota_Amos</a>
                </li>
            </ul>
        </div>
    </header>
    <section class="d-flex col-12 flex-column align-items-center my-3">
        <div class="col-10 d-flex flex-column align-items-center">
            <div class="d-flex align-items-end justify-content-center">
                <?php if(isset($mascota_amos_list)){?>
                    <form class="divListaSelect d-flex align-items-center" action="<?= base_url('inicio/amo_mascotas')?>" method="post" id="formularioAmoMascotas">
                        <select class="me-2" name="listaAmos" id="ListaAmos">
                            <option value="" selected disabled>Seleccione un amo de la lista </option>
                            <?php foreach($mascota_amos_list as $amo){?>
                                <?='<option name="amo" value=' .$amo['idAmo']. '>' .$amo['nombreAmo']. ' ' . $amo['apellidoAmo']. '</option>' ?>
                            <?php } ?>
                        </select>
                        <input type="submit" class="btn btn-sm btn-outline btn-primary" value="buscar" form="formularioAmoMascotas">
                    </form> 
                <?php }elseif(isset($amo_mascotas_list)){ ?>
                    <form class="divListaSelect d-flex align-items-center" action="<?php base_url('inicio/mascota_amos')?>" method="post" id="formularioMascotaAmos">
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
                <div class="col-12 tabla d-flex align-items-center justify-content-center">
                    <?php if(isset($table)){echo "<div class='d-flex align-content-start'>"; if(isset($tipoTabla)){echo "<div class='agregarButton'><button class='btn p-1 m-2'>"; switch($tipoTabla){ case "Mascotas": echo "Agregar Mascota";break; case "Amos": echo "Agregar Amo";break; case "Veterinarios": echo "Agregar Veterinario";break; case "AmoMascotas": echo "Agregar Relacion Amo-Mascota";break; case "MascotaAmos": echo "Agregar Relacion Mascota-Amo";} echo "</button></div>";} echo $table."</div>";}else{?> 
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
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>

</html>
