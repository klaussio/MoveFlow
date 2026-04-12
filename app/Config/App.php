<?php
/**
 * App Configuration — Global constants
 */

// Application
define('APP_NAME', 'MoveFlow');
define('APP_VERSION', '1.0.0');

// URL — Adjust if your XAMPP setup differs
define('APP_URL', 'http://localhost/MoveFlow/public');

// Fare calculation
define('FARE_BASE', 5.00);          // R$ 5,00 base
define('FARE_PER_KM', 2.50);       // R$ 2,50 per km
define('FARE_PER_MIN', 0.40);      // R$ 0,40 per minute

// Pagination
define('PER_PAGE', 15);

// Session
define('SESSION_LIFETIME', 3600);   // 1 hour

// Ride statuses
define('RIDE_STATUS_REQUESTED', 'requested');
define('RIDE_STATUS_ACCEPTED', 'accepted');
define('RIDE_STATUS_IN_PROGRESS', 'in_progress');
define('RIDE_STATUS_COMPLETED', 'completed');
define('RIDE_STATUS_CANCELLED', 'cancelled');

// Driver statuses
define('DRIVER_STATUS_AVAILABLE', 'available');
define('DRIVER_STATUS_BUSY', 'busy');
define('DRIVER_STATUS_OFFLINE', 'offline');

// Status labels (Portuguese)
define('RIDE_STATUS_LABELS', [
    'requested' => 'Solicitada',
    'accepted' => 'Aceita',
    'in_progress' => 'Em Andamento',
    'completed' => 'Finalizada',
    'cancelled' => 'Cancelada',
]);

define('DRIVER_STATUS_LABELS', [
    'available' => 'Disponível',
    'busy' => 'Ocupado',
    'offline' => 'Offline',
]);

// Status CSS classes
define('RIDE_STATUS_CLASSES', [
    'requested' => 'badge-warning',
    'accepted' => 'badge-info',
    'in_progress' => 'badge-primary',
    'completed' => 'badge-success',
    'cancelled' => 'badge-danger',
]);

define('DRIVER_STATUS_CLASSES', [
    'available' => 'badge-success',
    'busy' => 'badge-warning',
    'offline' => 'badge-danger',
]);
