<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(true);
$routes->get('/desk', 'Suara24\Login::login');
$routes->get('hasil/data', 'Hasil::getHasilSuara');
$routes->get('hasil/getget', 'Hasil::getSuara');

$routes->add('suara24/logout', 'Suara24\Login::logout');

$routes->group('suara24', ['filter' => 'noadmin'], function ($routes) {
    $routes->add('', '\Login::login');
    $routes->add('login', 'Suara24\Login::login');
    $routes->add('lupapassword', 'Suara24\Login::lupapassword');
    $routes->add('resetpassword', 'Suara24\Login::resetpassword');
    $routes->get('send', 'Realtime::index');
});
