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
                    <a class=" text-reset text-decoration-none" href="<?= base_url()."mascota"?>">Mascotas</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url('amo')?>">Amos</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url('veterinario')?>">Veterinarios</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url()."amo_mascota"?>">Amo_Mascotas</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url()."mascota_amo"?>">Mascota_Amos</a>
                </li>
            </ul>
        </div>
    </header>
    <div class="container py-3">
        <div id="mensaje-success" class="alert d-none" role="alert"></div>
    </div>
    <section class="divFinalRel d-flex col-12 flex-column justify-content-center align-items-center mb-3">
        <div class="col-10 d-flex flex-column align-items-center">
            <div class="col-auto d-flex flex-column align-items-center justify-content-center">
                <form id="formNewRel" class="col-12 d-flex flex-column justify-content-around m-4 mt-2" action="<?=base_url()?><?php if(isset($AmosDisponibles)) echo "mascota_amos";elseif(isset($MascotasDisponibles)) echo "amo_mascotas"?>/new_relacion_<?php if(isset($AmosDisponibles)) echo "mascota_amo";elseif(isset($MascotasDisponibles)) echo "amo_mascota"?>" method="post">
                    <div class="col-auto mb-4 d-flex flex-column">
                        <label for="<?php if(isset($AmosDisponibles)) echo "amoNewRelacion";elseif(isset($MascotasDisponibles)) echo "mascotaNewRelacion"?>" class="form-label"><?php if(isset($AmosDisponibles)) echo "seleccione al nuevo amo";elseif(isset($MascotasDisponibles)) echo "Seleccione a la nueva mascota"?></label>
                        <select name="<?php if(isset($AmosDisponibles)) echo "amoNewRelacion";elseif(isset($MascotasDisponibles)) echo "mascotaNewRelacion"?>" id="<?php if(isset($AmosDisponibles)) echo "amoNewRelacion";elseif(isset($MascotasDisponibles)) echo "mascotaNewRelacion"?>">
                            <?php
                                if(isset($AmosDisponibles)){
                                    $i=0;
                                    foreach($AmosDisponibles AS $amo){?>
                                        <option value="<?= $amo["idAmo"]?>" <?= "selected" ? $i=0 : "" ?>><?= $amo["nombreAmo"]." ".$amo["apellidoAmo"]?></option>
                                    <?php }
                                }elseif(isset($MascotasDisponibles)){
                                    $i=0;
                                    foreach($MascotasDisponibles AS $mascota){?>
                                        <option value="<?= $mascota["idMascota"]?>" <?= "selected" ? $i=0 : "" ?>><?= $mascota["nombreMascota"]?></option>
                                    <?php }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto d-flex flex-column align-items-center justify-content-around mb-4">
                        <label for="fechaNewRel" class="form-label">Fecha del suceso</label>
                        <input type="datetime-local" class="form-control <?= session('errors.fechaNewRel') ? 'is-invalid' : '' ?>" value="<?= old('fechaNewRel') ?>" id="fechaNewRel" name="fechaNewRel" max="<?php date_default_timezone_set("America/Argentina/Buenos_Aires"); echo date("Y-m-d H:i");?>" >
                        <div class="col-10 invalid-feedback">
                            <?= str_replace("fechaNewRel","La fecha del suceso",session('errors.fechaNewRel')) ?? '' ?>
                        </div>
                    </div>
                    <div class="d-grid d-md-flex justify-content-md-end">
                        <input type="text" name="idNewRel" id="idNewRel<?php if(isset($idAmoNewRelacion)) echo "Amo";elseif(isset($idMascotaNewRelacion)) echo "Mascota";?>" hidden value="<?php if(isset($idAmoNewRelacion)) echo $idAmoNewRelacion;elseif(isset($idMascotaNewRelacion)) echo $idMascotaNewRelacion;?>">
                        <button type="submit" class="btn btn-primary">Iniciar Relacion</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
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
    </script>
</html>
