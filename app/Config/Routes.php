<?php
/**
 * Routes — Application route definitions
 */

// Auth
Router::get('login', 'AuthController', 'loginForm');
Router::post('login', 'AuthController', 'login');
Router::get('logout', 'AuthController', 'logout');

// Dashboard
Router::get('', 'DashboardController', 'index');
Router::get('dashboard', 'DashboardController', 'index');
Router::get('api/dashboard/stats', 'DashboardController', 'stats');

// Drivers
Router::get('drivers', 'DriverController', 'index');
Router::get('drivers/create', 'DriverController', 'create');
Router::post('drivers/store', 'DriverController', 'store');
Router::get('drivers/edit/{id}', 'DriverController', 'edit');
Router::post('drivers/update/{id}', 'DriverController', 'update');
Router::post('drivers/delete/{id}', 'DriverController', 'delete');
Router::post('drivers/toggle-status/{id}', 'DriverController', 'toggleStatus');
Router::get('api/drivers/available', 'DriverController', 'available');

// Passengers
Router::get('passengers', 'PassengerController', 'index');
Router::get('passengers/create', 'PassengerController', 'create');
Router::post('passengers/store', 'PassengerController', 'store');
Router::get('passengers/edit/{id}', 'PassengerController', 'edit');
Router::post('passengers/update/{id}', 'PassengerController', 'update');
Router::post('passengers/delete/{id}', 'PassengerController', 'delete');

// Rides
Router::get('rides', 'RideController', 'index');
Router::get('rides/create', 'RideController', 'create');
Router::post('rides/store', 'RideController', 'store');
Router::get('rides/show/{id}', 'RideController', 'show');
Router::post('rides/accept/{id}', 'RideController', 'accept');
Router::post('rides/start/{id}', 'RideController', 'start');
Router::post('rides/complete/{id}', 'RideController', 'complete');
Router::post('rides/cancel/{id}', 'RideController', 'cancel');
Router::post('rides/assign-driver/{id}', 'RideController', 'assignDriver');
Router::get('api/rides/calculate-fare', 'RideController', 'calculateFare');

// Dispatch
Router::dispatch();
