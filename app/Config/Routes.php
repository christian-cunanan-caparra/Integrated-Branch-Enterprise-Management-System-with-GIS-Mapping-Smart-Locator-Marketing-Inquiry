<?php

namespace Config;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Auth Routes
$routes->get('/', 'Auth::index');
$routes->get('admin/login', 'Auth::index');
$routes->post('admin/login', 'Auth::login');
$routes->get('admin/logout', 'Auth::logout');

// Protected Admin Routes
$routes->get('admin/dashboard', 'Admin::index', ['filter' => 'auth']);
$routes->get('admin/branches', 'Branch::index', ['filter' => 'auth']);
$routes->get('admin/branches-map', 'Branch::index', ['filter' => 'auth']);
$routes->get('admin/settings', 'Settings::index', ['filter' => 'auth']);

// Branch API Routes
$routes->get('branch/search', 'Branch::search', ['filter' => 'auth']);
$routes->get('branch/get/(:num)', 'Branch::getBranch/$1', ['filter' => 'auth']);
$routes->get('branch/stats', 'Branch::getStats', ['filter' => 'auth']);
$routes->get('branch/coordinates', 'Branch::getAllCoordinates', ['filter' => 'auth']);

// Product Routes (protected)
$routes->get('admin/products', 'Product::index', ['filter' => 'auth']);
$routes->get('product/get/(:num)', 'Product::getProduct/$1', ['filter' => 'auth']);
$routes->get('product/stats', 'Product::getStats', ['filter' => 'auth']);

// Inquiry Routes (Public)
$routes->get('inquiry', 'Inquiry::index');
$routes->post('inquiry/submit', 'Inquiry::submit');
$routes->get('inquiry/suggestBranch', 'Inquiry::suggestBranch');
$routes->get('inquiry/searchCities', 'Inquiry::searchCities');
$routes->get('inquiry/getCitiesByProvince', 'Inquiry::getCitiesByProvince');
$routes->get('inquiry/getProvinces', 'Inquiry::getProvinces');

// Inquiry Routes (Admin - Protected)
$routes->get('admin/inquiries', 'Inquiry::adminIndex', ['filter' => 'auth']);
$routes->post('inquiry/updateStatus/(:num)', 'Inquiry::updateStatus/$1', ['filter' => 'auth']);
$routes->get('inquiry/get/(:num)', 'Inquiry::getInquiry/$1', ['filter' => 'auth']);