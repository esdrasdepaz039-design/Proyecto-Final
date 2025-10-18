<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Redirigir la página principal al login
$routes->get('/', 'Auth::login');

// Rutas de autenticación
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/authenticate', 'Auth::authenticate');
$routes->get('/auth/logout', 'Auth::logout');

// Rutas protegidas (todas con filtro de autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'Dashboard::index');
    
    // Rutas para libros
    $routes->get('/books', 'Books::index');
    $routes->get('/books/new', 'Books::new');
    $routes->post('/books/create', 'Books::create');
    
    // Rutas para clientes
    $routes->get('/clients', 'Clients::index');
    $routes->get('/clients/new', 'Clients::new');
    $routes->post('/clients/create', 'Clients::create');
    
    // Rutas para préstamos
    $routes->get('/rentals', 'Rentals::index');
    $routes->get('/rentals/new', 'Rentals::new');
    $routes->post('/rentals/create', 'Rentals::create');
    
    // Rutas para multas
    $routes->get('/fines', 'Fines::index');
});
