CREATE DATABASE IF NOT EXISTS laravel_autok CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laravel_autok;



DROP TABLE IF EXISTS car_models;
DROP TABLE IF EXISTS manufacturers;

CREATE TABLE manufacturers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    country VARCHAR(100) NULL,
    founded_year SMALLINT UNSIGNED NULL,
    website VARCHAR(255) NULL,
    logo_url VARCHAR(500) NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE car_models (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    manufacturer_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    model_code VARCHAR(30) NOT NULL UNIQUE,
    model_year SMALLINT UNSIGNED NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    body_type VARCHAR(50) NULL,
    fuel_type VARCHAR(50) NULL,
    power_hp SMALLINT UNSIGNED NULL,
    image_url VARCHAR(500) NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT car_models_manufacturer_id_foreign FOREIGN KEY (manufacturer_id) REFERENCES manufacturers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO manufacturers (id, name, country, founded_year, website, description, created_at, updated_at) VALUES
(1, 'Toyota', 'Japán', 1937, 'https://www.toyota.com', 'Japán autógyártó, a világ egyik legnagyobb járműgyártója, a hibrid hajtás úttörője.', NOW(), NOW()),
(2, 'Volkswagen', 'Németország', 1937, 'https://www.volkswagen.de', 'Wolfsburgi központú német autógyártó, a Volkswagen-konszern névadója.', NOW(), NOW()),
(3, 'Ford', 'USA', 1903, 'https://www.ford.com', 'Amerikai autógyártó, a futószalagos tömeggyártás úttörője.', NOW(), NOW()),
(4, 'Skoda', 'Csehország', 1895, 'https://www.skoda-auto.com', 'Mladá Boleslav-i cseh autógyártó, a Volkswagen-csoport tagja.', NOW(), NOW()),
(5, 'BMW', 'Németország', 1916, 'https://www.bmw.com', 'Müncheni prémium autó- és motorkerékpár-gyártó.', NOW(), NOW()),
(6, 'Suzuki', 'Japán', 1909, 'https://www.globalsuzuki.com', 'Japán gyártó, kisautóiról és terepjáróiról ismert; európai gyára Esztergomban működik.', NOW(), NOW());

INSERT INTO car_models (manufacturer_id, name, model_code, model_year, price, body_type, fuel_type, power_hp, created_at, updated_at) VALUES
(1, 'Corolla', 'TY-COR-23', 2023, 8500000.00, 'Kombi', 'Hibrid', 140, NOW(), NOW()),
(1, 'Yaris', 'TY-YAR-22', 2022, 6200000.00, 'Hatchback', 'Hibrid', 116, NOW(), NOW()),
(1, 'RAV4', 'TY-RAV-23', 2023, 13900000.00, 'SUV', 'Hibrid', 222, NOW(), NOW()),
(2, 'Golf', 'VW-GLF-23', 2023, 9800000.00, 'Hatchback', 'Benzin', 130, NOW(), NOW()),
(2, 'Passat', 'VW-PAS-22', 2022, 12500000.00, 'Kombi', 'Dízel', 150, NOW(), NOW()),
(2, 'Tiguan', 'VW-TIG-23', 2023, 14200000.00, 'SUV', 'Dízel', 150, NOW(), NOW()),
(3, 'Focus', 'FD-FOC-21', 2021, 7200000.00, 'Hatchback', 'Benzin', 125, NOW(), NOW()),
(3, 'Fiesta', 'FD-FIE-20', 2020, 5900000.00, 'Hatchback', 'Benzin', 100, NOW(), NOW()),
(3, 'Kuga', 'FD-KUG-22', 2022, 11200000.00, 'SUV', 'Hibrid', 190, NOW(), NOW()),
(4, 'Octavia', 'SK-OCT-23', 2023, 9600000.00, 'Kombi', 'Dízel', 116, NOW(), NOW()),
(4, 'Fabia', 'SK-FAB-22', 2022, 5800000.00, 'Hatchback', 'Benzin', 95, NOW(), NOW()),
(4, 'Kodiaq', 'SK-KOD-23', 2023, 15100000.00, 'SUV', 'Dízel', 150, NOW(), NOW()),
(5, '320i', 'BM-320-23', 2023, 16900000.00, 'Sedan', 'Benzin', 184, NOW(), NOW()),
(5, 'X3', 'BM-X3-22', 2022, 21500000.00, 'SUV', 'Dízel', 190, NOW(), NOW()),
(5, 'i4', 'BM-I4-23', 2023, 19800000.00, 'Sedan', 'Elektromos', 340, NOW(), NOW()),
(6, 'Swift', 'SZ-SWI-23', 2023, 5600000.00, 'Hatchback', 'Hibrid', 83, NOW(), NOW()),
(6, 'Vitara', 'SZ-VIT-23', 2023, 8300000.00, 'SUV', 'Hibrid', 129, NOW(), NOW()),
(6, 'Jimny', 'SZ-JIM-22', 2022, 7900000.00, 'SUV', 'Benzin', 102, NOW(), NOW());
