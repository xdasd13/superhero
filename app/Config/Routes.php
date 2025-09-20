<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Reportes
$routes->get('/reporte/r1', 'ReporteController::index');
$routes->get('/reporte/r2', 'ReporteController::reporte2');
$routes->get('/reporte/r3', 'ReporteController::reporte3');

// Filtros
$routes->get('/filtros', 'FiltrosController::index');
$routes->post('/filtros/generarPDF', 'FiltrosController::generarPDF');

// Hero Search System
$routes->get('/hero', 'HeroController::index');
$routes->post('/hero/search', 'HeroController::search');
$routes->get('/hero/(:num)', 'HeroController::getHero/$1');
$routes->post('/hero/generatePDFData', 'HeroController::generatePDFData');

// Test System
$routes->get('/test', 'TestController::index');
$routes->get('/test/search', 'TestController::testSearch');