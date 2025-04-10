-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS frictionwell_db;
USE frictionwell_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id CHAR(36) PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    last_login TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de planes de suscripción
CREATE TABLE IF NOT EXISTS subscription_plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    monthly_price DECIMAL(8,2) NOT NULL,
    calculation_limit INT NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de suscripciones de usuarios
CREATE TABLE IF NOT EXISTS user_subscriptions (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    plan_id BIGINT UNSIGNED NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    purchase_token VARCHAR(500) NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES subscription_plans(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de versiones de la aplicación
CREATE TABLE IF NOT EXISTS app_versions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de cálculos
CREATE TABLE IF NOT EXISTS calculations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar planes de suscripción
INSERT INTO subscription_plans (name, monthly_price, calculation_limit, is_active, created_at, updated_at) VALUES
('Free', 0.00, 10, true, NOW(), NOW()),
('Premium', 2.99, 25, true, NOW(), NOW()),
('Premium+', 4.99, 50, true, NOW(), NOW());

-- Crear usuario administrador (password: Admin@123)
INSERT INTO users (id, email, password, created_at, updated_at) VALUES
(UUID(), 'info@drillsoft.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

-- Asignar suscripción Premium al administrador
INSERT INTO user_subscriptions (id, user_id, plan_id, start_date, end_date, purchase_token, is_active, created_at, updated_at)
SELECT 
    UUID(),
    (SELECT id FROM users WHERE email = 'info@drillsoft.com'),
    (SELECT id FROM subscription_plans WHERE name = 'Premium'),
    NOW(),
    DATE_ADD(NOW(), INTERVAL 1 YEAR),
    'ADMIN_TOKEN',
    true,
    NOW(),
    NOW(); 