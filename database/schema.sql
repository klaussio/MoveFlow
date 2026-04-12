-- ============================================
-- MoveFlow — Database Schema
-- MySQL 8+
-- ============================================

CREATE DATABASE IF NOT EXISTS moveflow
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE moveflow;

-- ============================================
-- Users (Admin)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin') NOT NULL DEFAULT 'admin',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Drivers
-- ============================================
CREATE TABLE IF NOT EXISTS drivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    cnh VARCHAR(20) NOT NULL,
    vehicle_plate VARCHAR(10) NOT NULL,
    vehicle_model VARCHAR(80) NOT NULL,
    vehicle_color VARCHAR(30) NOT NULL,
    status ENUM('available', 'busy', 'offline') NOT NULL DEFAULT 'available',
    rating DECIMAL(3,2) NOT NULL DEFAULT 5.00,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_drivers_status (status),
    INDEX idx_drivers_cpf (cpf),
    INDEX idx_drivers_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Passengers
-- ============================================
CREATE TABLE IF NOT EXISTS passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_passengers_cpf (cpf),
    INDEX idx_passengers_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Rides
-- ============================================
CREATE TABLE IF NOT EXISTS rides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    passenger_id INT NOT NULL,
    driver_id INT NULL,
    origin_address VARCHAR(255) NOT NULL,
    origin_lat DECIMAL(10,7) DEFAULT 0,
    origin_lng DECIMAL(10,7) DEFAULT 0,
    destination_address VARCHAR(255) NOT NULL,
    destination_lat DECIMAL(10,7) DEFAULT 0,
    destination_lng DECIMAL(10,7) DEFAULT 0,
    status ENUM('requested', 'accepted', 'in_progress', 'completed', 'cancelled') NOT NULL DEFAULT 'requested',
    distance_km DECIMAL(8,2) NOT NULL DEFAULT 0,
    estimated_duration_min INT NOT NULL DEFAULT 0,
    fare DECIMAL(10,2) NOT NULL DEFAULT 0,
    requested_at DATETIME NULL,
    accepted_at DATETIME NULL,
    started_at DATETIME NULL,
    completed_at DATETIME NULL,
    cancelled_at DATETIME NULL,
    cancel_reason TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_rides_passenger FOREIGN KEY (passenger_id) REFERENCES passengers(id) ON DELETE CASCADE,
    CONSTRAINT fk_rides_driver FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL,
    INDEX idx_rides_status (status),
    INDEX idx_rides_passenger (passenger_id),
    INDEX idx_rides_driver (driver_id),
    INDEX idx_rides_created (created_at),
    INDEX idx_rides_completed (completed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
