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
    <?php
        if(isset($cabeza))echo $cabeza;
    ?>
    <div class="container py-3">
        <div id="mensaje-success" class="alert d-none" role="alert"></div>
    </div>
    <section class="divFinalRel d-flex col-12 flex-column justify-content-center align-items-center mb-3">
        <div class="col-10 d-flex flex-column align-items-center">
            <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                <h2 class="m-4 mb-2">¿Porque finaliza la relacion?</h2>
                <form id="formFinalRel" class="col-12 d-flex flex-column justify-content-around m-4 mt-2" action="<?=base_url()?>inicio/finalizar_relacion" method="post">
                    <div class="d-flex align-items-center justify-content-around mb-3">
                        <div class="col-auto">
                            <label for="motivoFinalRel" class="form-label col-12">Motivo</label>
                            <select name="motivoFinalRel" id="motivoFinalRel">
                                <option value="Venta" selected>Venta</option>
                                <option value="Fallecimiento">Fallecimiento</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="fechaFinalRel" class="form-label">Fecha del suceso</label>
                            <input type="datetime-local" class="form-control <?= session('errors.fechaFinalRel') ? 'is-invalid' : '' ?>" value="<?= old('fechaFinalRel') ?>" id="fechaFinalRel" name="fechaFinalRel" max="<?php date_default_timezone_set("America/Argentina/Buenos_Aires"); echo date("Y-m-d H:i");?>" >
                            <div class="col-10 invalid-feedback">
                                <?= str_replace("fechaFinalRel","La fecha del suceso",session('errors.fechaFinalRel')) ?? '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid d-md-flex justify-content-md-end">
                        <input type="text" name="idFinalRel" id="idFinalRel" hidden value="<?php if(isset($idRel)) echo $idRel;?>">
                        <button type="submit" class="btn btn-primary">Finalizar Relacion</button>
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
