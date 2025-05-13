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
    <div class="container py-3">
        <div id="mensaje-success" class="alert d-none" role="alert"></div>
    </div>
    <section class="d-flex col-12 flex-column align-items-center mb-3">
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
                <div class="col-12 tabla d-flex align-items-start justify-content-center">
                    <?php if(isset($table)){echo "<div class='d-flex flex-column align-content-start'>";}
                    if(isset($tipoTabla)){
                        echo "<div class='agregarButton col-12 d-flex aling-items-center justify-content-between'>";
                        switch($tipoTabla){
                            case "Mascotas": echo "<button class='btn p-1 m-2' data-bs-toggle='modal' data-bs-target='#modalAgregarMascotas'>Agregar Mascota</button><button class='btn p-1 m-2'><a class='text-reset text-decoration-none' href='".base_url()."inicio/todas_mascotas'>Mostrar Todas</a></button>";break;
                            case "Amos": echo "<button class='btn p-1 m-2' data-bs-toggle='modal' data-bs-target='#modalAgregarAmos'>Agregar Amo</button>";break;
                            case "Veterinarios": echo "<button class='btn p-1 m-2' data-bs-toggle='modal' data-bs-target='#modalAgregarVeterinarios'>Agregar Veterinario</button>";break; 
                            case "AmoMascotas": if(!isset($invalidNew)){
                                                    echo "<button class='btn p-1 m-2'>".$newRelAmoMasc."</button>";
                                                }break; 
                            case "MascotaAmos": if(!isset($invalidNew)){
                                                    echo "<button class='btn p-1 m-2'>".$newRelMascAmo."</button>";
                                                }
                        } 
                        echo "</div>";
                    }
                    if(isset($table)){ 
                        echo $table."</div>";
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
                <form class="d-flex flex-wrap" action="<?=base_url()?>inicio/alta_mascotas " method="post">
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
                <form class="d-flex flex-wrap" action="<?=base_url()?>inicio/alta_amos " method="post">
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
                <form action="<?=base_url()?>inicio/alta_veterinarios" method="post">
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
