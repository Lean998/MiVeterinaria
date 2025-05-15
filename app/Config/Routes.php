<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Inicio::index');
$routes->get('/inicio', 'Inicio::index');
$routes->get('/inicio/finalizar_relacion/(:num)', 'Inicio::finalizarRelacion/$1');
$routes->post('/inicio/finalizar_relacion', 'Inicio::finalizarRelacion');
$routes->post('/inicio/eliminar', 'Inicio::eliminar');
$routes->get('/inicio/modificar/(:segment)/(:num)', 'Inicio::modificar/$1/$2');
$routes->post('/inicio/update', 'Inicio::update');
$routes->get('/inicio/mascotas', 'Inicio::mascotas');

$routes->get('/mascota', 'Mascota::index');
$routes->get('/mascota/todas_mascotas', 'Mascota::todasMascotas');
$routes->post('/mascota/alta_mascotas', 'Mascota::altaMascotas');
$routes->get('/mascota/eliminar_mascota/(:num)', 'Mascota::eliminarMascota/$1');
$routes->get('/mascota/mascota_difunta/(:num)', 'Mascota::mascotaDifunta/$1');
$routes->post('/mascota/mascota_difunta', 'Mascota::mascotaDifunta');

$routes->get('/amo', 'Amo::index');
$routes->get('/amo/eliminar_amo/(:num)', 'Amo::eliminarAmo/$1');
$routes->post('/amo/alta_amos', 'Amo::altaAmos');

$routes->get('/veterinario', 'Veterinario::index');

$routes->get('/amo_mascotas', 'AmoMascota::index');
$routes->post('/amo_mascotas', 'AmoMascota::index');
$routes->get('/amo_mascotas/new_relacion_amo_mascota/(:num)', 'AmoMascota::newRelacionAmoMascota/$1');
$routes->post('/amo_mascotas/new_relacion_amo_mascota', 'AmoMascota::newRelacionAmoMascota');

$routes->get('/mascota_amos', 'MascotaAmo::index');
$routes->post('/mascota_amos', 'MascotaAmo::index');
$routes->get('/mascota_amos/new_relacion_mascota_amo/(:num)', 'MascotaAmo::newRelacionMascotaAmo/$1');
$routes->post('/mascota_amos/new_relacion_mascota_amo', 'MascotaAmo::newRelacionMascotaAmo');

$routes->post('/veterinario/alta_veterinarios', 'Veterinario::altaVeterinarios');
$routes->get('/veterinario/eliminar_veterinario/(:num)', 'Veterinario::eliminarVeterinario/$1');

$routes->get("/miveterinaria","MiVeterinariaDB::index");
