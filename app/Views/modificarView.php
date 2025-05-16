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
    <?php
        if(isset($cabeza))echo $cabeza;
    ?>
    <div class="container py-3">
        <div id="mensaje-success" class="alert d-none" role="alert"></div>
    </div>
    
    <div class="container my-4 d-flex flex-column justify-content-center align-items-center divModificar">
        <h2 class="col-12 text-center pt-4">Modificar <?= ucfirst($tipo) ?></h2>
        <form class="col-12 px-4 pb-4" action="<?= base_url('inicio/update') ?>" method="post">
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
                <p class="text-danger">Tipo de entidad no válido.</p>
            <?php } ?>   
        </form>
    </div>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
</html>