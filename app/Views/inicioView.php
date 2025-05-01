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
        <div class="col-4"><img class="img-fluid" src="" alt="logo"></div>
        <div class="col-4"></div>
    </header>
    <section class="d-flex col-12 flex-column align-items-center my-3">
        <div class="col-8">
            <ul class="d-flex">
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
        <div class="col-10 d-flex flex-column align-items-center">
            <div>
                <?php if(isset($amos_mascota_list)){?>
                    <form action="<?php base_url('inicio/amo_mascotas')?>" method="post" id="formularioAmoMascotas">
                        <select name="listaAmos" id="ListaAmos"></select>
                            <option value="" disabled>Seleccione un amo de la lista </option>
                            <?php foreach($amos_mascota_list as $amo){?>
                                <?='<option name="amo" value=' .$amo['idAmo']. '>' .$amo['nombreAmo']. ' ' . $amo['apellidoAmo']. '</option>' ?>
                            <?php } ?>
                        </select>
                        <input type="submit" class="btn btn-sm btn-outline btn-primary" value="formularioAmoMascotaSend" form="formularioAmoMascota">
                    </form> 
                <?php }elseif(isset($mascotas_amo_list)){ ?>
                    <form action="<?php base_url('inicio/mascota_amos')?>" method="post" id="formularioMascotaAmos">
                        <select name="ListaMascotas" id="ListaMascotas"></select>
                            <option value="" disabled>Seleccione una mascota de la lista </option>
                            <?php foreach($mascotas_amo_list as $mascota){?>
                                <?='<option name="mascota" value=' .$mascota['idMascota']. '>' . $mascota['nombreMascota']. '</option>' ?>
                            <?php } ?>
                        </select>
                        <input type="submit" class="btn btn-sm btn-outline btn-primary" value="formularioMascotaAmosSend" form="formularioMascotaAmos"> 
                    </form>
                <?php }?>
            </div>
            <div class="col-12 d-flex flex-column align-items-center">
                <div class="col-12">
                    <?php if(isset($table))echo $table;else{?> 
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
