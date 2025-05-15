<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= substr(base_url(),0,-17) ?>Plantilla/Css/inicio.css">
    <title>Modificar</title>
</head>
<body>
    <header class="d-flex col-12 align-items-center justify-content-between">
        <div class="col-auto ms-3"><a href="<?= base_url()."inicio"?>"><img class="img-fluid" src="<?= substr(base_url(),0,-17)?>Plantilla/imgs/MenuLogo.jpg" alt="Logo"></a></div>
        <div class="col-10">
            <ul class="d-flex mb-0">
                <li class="dropdown-item">
                    <a class=" text-reset text-decoration-none" href="<?= base_url()."/mascota"?>">Mascotas</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url('amo')?>">Amos</a>
                </li>
                <li class="dropdown-item">
                    <a class="dropdown-item text-reset text-decoration-none" href="<?= base_url('veterinario')?>">Veterinarios</a>
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
    
    <div class="container my-4">
        <h2>Modificar <?= ucfirst($tipo) ?>

        <form action="<?= base_url('inicio/update') ?>" method="post">
            <input type="hidden" name="id" value="<?= $entidad['id' . ucfirst($tipo)] ?>">
            <input type="hidden" name="tipo" value="<?= $tipo ?> ">

            <?php if($tipo === 'mascota') { ?>
                <div class="mb-3">
                    <label for="nombreMascota" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombreMascota" name="nombreMascota" value="<?= old('nombreMascota', $entidad['nombreMascota']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="especieMascota" class="form-label">Especie:</label>
                    <input type="text" class="form-control" id="especieMascota" name="especieMascota" value="<?= old('especieMascota', $entidad['especieMascota']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="razaMascota" class="form-label">Raza:</label>
                    <input type="text" class="form-control" id="razaMascota" name="razaMascota" value="<?= old('razaMascota', $entidad['razaMascota']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="edadMascota" class="form-label">Edad:</label>
                    <input type="number" class="form-control" id="edadMascota" name="edadMascota" value="<?= old('edadMascota', $entidad['edadMascota']) ?>" required>
                </div>
            <?php } elseif($tipo === 'amo') { ?>
                <div class="mb-3">
                    <label for="nombreAmo" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombreAmo" name="nombreAmo" value="<?= old('nombreAmo', $entidad['nombreAmo']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="apellidoAmo" class="form-label">Apellido:</label>
                    <input type="text" class="form-control" id="apellidoAmo" name="apellidoAmo" value="<?= old('apellidoAmo', $entidad['apellidoAmo']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="telefonoAmo" class="form-label">Telefono:</label>
                    <input type="text" class="form-control" id="telefonoAmo" name="telefonoAmo" value="<?= old('telefonoAmo', $entidad['telefonoAmo']) ?>" required>
                </div>
            <?php } elseif ($tipo === 'veterinario') { ?>
                <div class="mb-3">
                    <label for="nombreVeterinario" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombreVeterinario" name="nombreVeterinario" value="<?= old('nombreVeterinario', $entidad['nombreVeterinario']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="apellidoVeterinario" class="form-label">Apellido:</label>
                    <input type="text" class="form-control" id="apellidoVeterinario" name="apellidoVeterinario" value="<?= old('apellidoVeterinario', $entidad['apellidoVeterinario']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="especialidadVeterinario" class="form-label">Especialidad:</label>
                    <input type="text" class="form-control" id="especialidadVeterinario" name="especialidadVeterinario" value="<?= old('especialidadVeterinario', $entidad['especialidadVeterinario']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="telefonoVeterinario" class="form-label">Telefono::</label>
                    <input type="text" class="form-control" id="telefonoVeterinario" name="telefonoVeterinario" value="<?= old('telefonoVeterinario', $entidad['telefonoVeterinario']) ?>" required>
                </div>
            <?php } else { ?>
                <p> class="text-danger">Tipo de entidad no válido.</p>
            <?php } ?>    
            
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="<?= base_url('inicio/' . ($tipo === 'mascota' ? 'mascotas' : ($tipo === 'amo' ? 'amos' : ($tipo === 'veterinario' ? 'veterinarios' : '')))) ?>" class="btn btn-secondary">Cancelar</a>

        </form>
    </div>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
<?php if (session()->getFlashdata('mensaje')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('mensaje'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
</html>