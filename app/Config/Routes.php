<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Inicio::index');
$routes->get('/inicio', 'Inicio::index');
$routes->get('/mascota', 'Mascota::index');
$routes->get('/mascota/todas_mascotas', 'Mascota::todasMascotas');
$routes->get('/amo', 'Amo::index');
$routes->get('/veterinario', 'Veterinario::index');
$routes->get('/amo_mascotas', 'AmoMascota::amoMascotas');
$routes->post('/amo_mascotas', 'AmoMascota::amoMascotas');
$routes->get('/mascota_amos', 'MascotaAmo::mascotaAmos');
$routes->post('/mascota_amos', 'MascotaAmo::mascotaAmos');
$routes->post('/mascota/alta_mascotas', 'Mascota::altaMascotas');
$routes->post('/amo/alta_amos', 'Amo::altaAmos');
$routes->post('/veterinario/alta_veterinarios', 'Veterinario::altaVeterinarios');
$routes->get('/inicio/finalizar_relacion/(:num)', 'Inicio::finalizarRelacion/$1');
$routes->post('/inicio/finalizar_relacion', 'Inicio::finalizarRelacion');
$routes->get('/mascota_amo/new_relacion_mascota_amo/(:num)', 'MascotaAmo::newRelacionMascotaAmo/$1');
$routes->post('/moscota_amo/new_relacion_mascota_amo', 'MascotaAmo::newRelacionMascotaAmo');
$routes->get('/amo_mascota/new_relacion_amo_mascota/(:num)', 'AmoMascota::newRelacionAmoMascota/$1');
$routes->post('/amo_mascota/new_relacion_amo_mascota', 'AmoMascota::newRelacionAmoMascota');
$routes->get('/mascota/eliminar_mascota/(:num)', 'Mascota::eliminarMascota/$1');
$routes->get('/amo/eliminar_amo/(:num)', 'Amo::eliminarAmo/$1');
$routes->get('/veterinario/eliminar_veterinario/(:num)', 'Veterinario::eliminarVeterinario/$1');
$routes->post('/inicio/eliminar', 'Inicio::eliminar');
$routes->get('/mascota/mascota_difunta/(:num)', 'Mascota::mascotaDifunta/$1');
$routes->post('/mascota/mascota_difunta', 'Mascota::mascotaDifunta');
$routes->get('/inicio/modificar/(:segment)/(:num)', 'Inicio::modificar/$1/$2');
$routes->get("/miveterinaria","MiVeterinariaDB::index");
