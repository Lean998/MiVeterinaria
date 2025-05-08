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

$routes->get("/miveterinaria","MiVeterinariaDB::index");
