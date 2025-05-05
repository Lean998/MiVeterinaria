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
$routes->get('/inicio/amo_mascota', 'Inicio::amoMascota');
$routes->get('/inicio/mascota_amos', 'Inicio::mascotaAmos');

$routes->get("/miveterinaria","MiVeterinariaDB::index");
