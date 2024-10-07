<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/', 'Chart::index');
$routes->get('hasil/data', 'Hasil::getHasilSuara');
$routes->get('hasil/getget', 'Hasil::getSuara');
