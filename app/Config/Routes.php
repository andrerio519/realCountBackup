<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/admin', 'Admin::index');
$routes->get('/kecamatan', 'Kota::index', ['filter' => 'usersAuth']);
$routes->get('/kecamatan/detail/(:num)', 'Kecamatan::detail/$1', ['filter' => 'usersAuth']);
$routes->get('/kelurahan/detail/(:num)', 'Kelurahan::detail/$1', ['filter' => 'usersAuth']);
$routes->get('/tps/detail/(:num)', 'Tps::detail/$1', ['filter' => 'usersAuth']);
$routes->get('/saksi', 'Tps::saksi', ['filter' => 'usersAuth']);
$routes->get('/users', 'Users::index', ['filter' => 'usersAuth']);
$routes->get('/caleg', 'Caleg::index', ['filter' => 'usersAuth']);
$routes->get('/allTpsData', 'Tps::dataTPS', ['filter' => 'usersAuth']);
$routes->get('/data', 'Tps::data', ['filter' => 'usersAuth']);
$routes->post('/users/getUser', 'Users::getUser', ['filter' => 'usersAuth']);
$routes->post('/users/userEdit', 'Users::userEdit', ['filter' => 'usersAuth']);
$routes->post('/kota/kecamatanCreate', 'Kota::kecamatanCreate', ['filter' => 'usersAuth']);
$routes->post('/kecamatan/kelurahanCreate/(:num)', 'Kecamatan::kelurahanCreate/$1', ['filter' => 'usersAuth']);
$routes->post('/kota/adminKecamatanCreate', 'Kota::adminKecamatanCreate', ['filter' => 'usersAuth']);
$routes->post('/kota/kecamatanEdit', 'Kota::kecamatanEdit', ['filter' => 'usersAuth']);
$routes->post('/kecamatan/adminKelurahan/(:num)', 'Kecamatan::adminKelurahan/$1', ['filter' => 'usersAuth']);
$routes->post('/kecamatan/kelurahanEdit', 'Kecamatan::kelurahanEdit', ['filter' => 'usersAuth']);
$routes->post('/kota/getKecamatan', 'Kota::getKecamatan', ['filter' => 'usersAuth']);
$routes->post('/kecamatan/getKelurahan', 'Kecamatan::getKelurahan', ['filter' => 'usersAuth']);
$routes->post('/kelurahan/saksiCreate/(:num)', 'Kelurahan::saksiCreate/$1', ['filter' => 'usersAuth']);
$routes->post('/kelurahan/saksiEdit', 'Kelurahan::saksiEdit', ['filter' => 'usersAuth']);
$routes->post('/tps/insert/(:num)', 'Tps::insert/$1', ['filter' => 'usersAuth']);
$routes->post('/caleg/createCalegkota', 'Caleg::createCalegkota', ['filter' => 'usersAuth']);
$routes->post('/caleg/editCalegKota', 'Caleg::editCalegKota', ['filter' => 'usersAuth']);
$routes->post('/caleg/getCaleg', 'Caleg::getCaleg', ['filter' => 'usersAuth']);
$routes->delete('/caleg/(:num)', 'Caleg::deleteCaleg/$1', ['filter' => 'usersAuth']);
$routes->delete('/kecamatan/detail/(:num)', 'Kota::deleteKecamatan/$1', ['filter' => 'usersAuth']);
$routes->delete('/kelurahan/detail/(:num)', 'Kecamatan::deleteKelurahan/$1', ['filter' => 'usersAuth']);
$routes->delete('/tps/detail/(:num)', 'Kelurahan::deleteTPS/$1', ['filter' => 'usersAuth']);
$routes->delete('/users/(:num)', 'Users::deleteUser/$1', ['filter' => 'usersAuth']);
$routes->get('/config', 'Config::index', ['filter' => 'usersAuth']);
$routes->post('/config/save', 'Config::save', ['filter' => 'usersAuth']);
$routes->post('/login/process', 'Login::process');
$routes->get('/logout', 'Login::logout');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
