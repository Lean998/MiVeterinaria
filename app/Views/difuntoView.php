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
    <section class="divDifunto d-flex col-12 flex-column justify-content-center align-items-center mb-3">
        <div class="col-10 d-flex flex-column align-items-center">
            <div class="col-6 d-flex flex-column align-items-center justify-content-center">
                <form id="formDifunto" class="col-12 d-flex flex-column justify-content-center align-items-center m-4 mt-2" action="<?=base_url()?>mascota/mascota_difunta" method="post">
                    <div class="d-flex flex-column align-items-center justify-content-around mb-3">
                        <h3 class="col-12 text-center m-5 mx-0">Lamentamos su perdida</h3>
                        <div class="col-10 m-3">
                            <label for="fechaDefuncion" class="form-label">Fecha de defuncion</label>
                            <input type="datetime-local" class="form-control <?= session('errors.fechaDefuncion') ? 'is-invalid' : '' ?>" value="<?= old('fechaDefuncion') ?>" id="fechaDefuncion" name="fechaDefuncion" max="<?php date_default_timezone_set("America/Argentina/Buenos_Aires"); echo date("Y-m-d H:i");?>" >
                            <div class="col-12 invalid-feedback">
                                <?= str_replace("fechaDefuncion","La fecha del suceso",session('errors.fechaDefuncion')) ?? '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-5 d-flex justify-content-end me-3">
                        <input type="text" name="id" id="id" hidden value="<?php if(isset($id)) echo $id;?>">
                        <button type="submit" class="btn btn-primary">Dar de baja</button>
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
