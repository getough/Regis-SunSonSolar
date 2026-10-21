<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public
$routes->get('/', 'Home::index');
$routes->get('privacy', 'Home::privacy');
$routes->get('register', 'Register::index');
$routes->post('register', 'Register::store');
$routes->addRedirect('registration', 'register');   // old URL
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->post('logout', 'Auth::logout');

// Any logged-in user
$routes->get('change-password', 'Auth::changePassword', ['filter' => 'auth']);
$routes->post('change-password', 'Auth::updatePassword', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Admin and HR only
$routes->group('staff', ['filter' => 'auth:admin,hr'], static function ($routes) {
    $routes->get('/', 'Staff::index');
    $routes->get('create', 'Staff::create');
    $routes->post('/', 'Staff::store');
    $routes->post('toggle/(:num)', 'Staff::toggle/$1');
});
