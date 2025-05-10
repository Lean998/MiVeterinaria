<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Inicio::index');
$routes->get('/inicio', 'Inicio::index');
$routes->get('/inicio/mascotas', 'Inicio::mascotas');
$routes->get('/inicio/amos', 'Inicio::amos');
$routes->get('/inicio/veterinarios', 'Inicio::veterinarios');
$routes->get('/inicio/amo_mascotas', 'Inicio::amoMascotas');
$routes->post('/inicio/amo_mascotas', 'Inicio::amoMascotas');
$routes->get('/inicio/mascota_amos', 'Inicio::mascotaAmos');
$routes->post('/inicio/mascota_amos', 'Inicio::mascotaAmos');
$routes->post('/inicio/alta_mascotas', 'Inicio::altaMascotas');
$routes->post('/inicio/alta_amos', 'Inicio::altaAmos');
$routes->post('/inicio/alta_veterinarios', 'Inicio::altaVeterinarios');
$routes->get('/inicio/finalizar_relacion/(:num)', 'Inicio::finalizarRelacion/$1');
$routes->post('/inicio/finalizar_relacion', 'Inicio::finalizarRelacion');
$routes->get('/inicio/new_relacion_mascota_amo/(:num)', 'Inicio::newRelacionMascotaAmo/$1');
$routes->post('/inicio/new_relacion_mascota_amo', 'Inicio::newRelacionMascotaAmo');
$routes->get('/inicio/new_relacion_amo_mascota/(:num)', 'Inicio::newRelacionAmoMascota/$1');
$routes->post('/inicio/new_relacion_amo_mascota', 'Inicio::newRelacionAmoMascota');

$routes->get("/miveterinaria","MiVeterinariaDB::index");
