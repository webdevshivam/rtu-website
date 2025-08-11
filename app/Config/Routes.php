<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'StudentController::index');

// Student Panel Routes
$routes->group('student', function($routes) {
    $routes->get('dashboard', 'StudentController::index');
    $routes->get('hierarchy', 'StudentController::getSubjectHierarchy');
    $routes->get('subtopic/(:num)', 'StudentController::getSubtopicContent/$1');
    $routes->post('content/(:num)/view', 'StudentController::incrementView/$1');
    $routes->get('search', 'StudentController::search');
    $routes->get('download/(:num)', 'StudentController::downloadFile/$1');
});

// Admin Panel Routes
$routes->group('admin', function($routes) {
    $routes->get('login', 'AdminController::login');
    $routes->post('authenticate', 'AdminController::authenticate');
    $routes->get('dashboard', 'AdminController::index');
    $routes->get('logout', 'AdminController::logout');
});
