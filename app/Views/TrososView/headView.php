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