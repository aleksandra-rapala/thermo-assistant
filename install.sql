DROP SCHEMA public CASCADE;
CREATE SCHEMA public;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(32) NOT NULL,
    surname VARCHAR(64) NOT NULL,
    e_mail VARCHAR(128) NOT NULL,
    password VARCHAR(60) NOT NULL
);

CREATE TABLE addresses (
    id SERIAL PRIMARY KEY,
    country VARCHAR(64) NOT NULL,
    district VARCHAR(64) NOT NULL,
    community VARCHAR(64) NOT NULL,
    location VARCHAR(64) NOT NULL,
    street VARCHAR(64) NOT NULL,
    building_no VARCHAR(16) NOT NULL,
    apartment_no VARCHAR(16) NOT NULL
);

CREATE TYPE building_destinations AS ENUM ('residential', 'service', 'residential-n-service', 'industrial');
CREATE TYPE consumptions AS ENUM ('little', 'standard', 'noticeable');

CREATE TABLE details (
    id SERIAL PRIMARY KEY,
    area DECIMAL(7, 3) NOT NULL,
    storeys INT NOT NULL,
    housemates INT NOT NULL,
    water_usage consumptions NOT NULL,
    energy_usage consumptions NOT NULL,
    destination building_destinations NOT NULL
);

CREATE TABLE buildings (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    address_id INT NOT NULL UNIQUE REFERENCES addresses(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    details_id INT NOT NULL UNIQUE REFERENCES details(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE modernizations (
    id SERIAL PRIMARY KEY,
    name VARCHAR(32) NOT NULL UNIQUE,
    label VARCHAR(64) NOT NULL UNIQUE
);

CREATE TYPE modernization_statuses AS ENUM ('planned', 'completed');

CREATE TABLE buildings_modernizations (
    id SERIAL PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    modernization_id INT NOT NULL REFERENCES modernizations(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    status modernization_statuses NOT NULL
);

CREATE TYPE combustion_chambers AS ENUM ('open', 'closed', 'none');

CREATE TABLE heater_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(16) NOT NULL UNIQUE,
    label VARCHAR(32) NOT NULL UNIQUE
);

CREATE TABLE heaters_to_install (
    id SERIAL PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    heater_type_id INT NOT NULL REFERENCES heater_types(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE thermal_classes (
    id SERIAL PRIMARY KEY,
    label VARCHAR(16) NOT NULL UNIQUE,
    name VARCHAR(64) NOT NULL UNIQUE,
    eco_project BOOLEAN NOT NULL
);

CREATE TABLE heaters (
    id SERIAL PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    heater_type_id INT NOT NULL DEFAULT 1 REFERENCES heater_types(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    power DECIMAL(7, 3) NOT NULL DEFAULT 0.000,
    combustion_chamber combustion_chambers DEFAULT 'closed' NOT NULL,
    efficiency DECIMAL(7, 3) NOT NULL DEFAULT 0.000,
    installation_year INT NOT NULL DEFAULT 0,
    production_year INT NOT NULL DEFAULT 0,
    data_source VARCHAR(255) NOT NULL DEFAULT '',
    dust_extractor BOOLEAN NOT NULL DEFAULT false,
    thermal_class_id INT NOT NULL DEFAULT 1 REFERENCES thermal_classes(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE fuels (
    id SERIAL PRIMARY KEY,
    name VARCHAR(16) UNIQUE NOT NULL,
    label VARCHAR(32) UNIQUE NOT NULL,
    unit VARCHAR(8) NOT NULL
);

CREATE TABLE buildings_fuels (
    id SERIAL PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    fuel_id INT NOT NULL REFERENCES fuels(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    consumption DECIMAL(7, 3) NOT NULL CHECK (consumption > 0),
    UNIQUE(building_id, fuel_id)
);

CREATE TABLE distributors (
    id SERIAL PRIMARY KEY,
    company_name VARCHAR(64) NOT NULL,
    address_id INT NOT NULL REFERENCES addresses(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE distributors_fuels (
    id SERIAL PRIMARY KEY,
    distributor_id INT NOT NULL REFERENCES distributors(id) ON UPDATE CASCADE ON DELETE CASCADE,
    fuel_id INT NOT NULL REFERENCES fuels(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE(distributor_id, fuel_id)
);

CREATE TABLE emission_rules (
    id SERIAL PRIMARY KEY,
    heater_type_id INT NOT NULL REFERENCES heater_types(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    heater_class INT NOT NULL REFERENCES thermal_classes(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    priority INT NOT NULL CHECK (priority > 0)
);

CREATE TABLE emission_indicators (
    id SERIAL PRIMARY KEY,
    co2 INT NOT NULL CHECK (co2 > 0),
    pm10 INT NOT NULL CHECK (pm10 > 0),
    pm25 INT NOT NULL CHECK (pm25 > 0),
    co INT NOT NULL CHECK (co > 0),
    nox INT NOT NULL CHECK (nox > 0),
    so2 INT NOT NULL CHECK (so2 > 0),
    bap INT NOT NULL CHECK (bap > 0)
);

CREATE TABLE emission_indicator_rules (
    id SERIAL PRIMARY KEY,
    fuel_id INT NOT NULL REFERENCES fuels ON UPDATE CASCADE ON DELETE CASCADE,
    emission_rule_id INT NOT NULL REFERENCES emission_rules(id) ON UPDATE CASCADE ON DELETE CASCADE,
    emission_indicator_id INT NOT NULL REFERENCES emission_indicators(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE subscriptions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(32) NOT NULL UNIQUE
);

CREATE TABLE buildings_subscriptions (
    id SERIAL PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    subscription_id INT NOT NULL REFERENCES subscriptions ON UPDATE CASCADE ON DELETE RESTRICT,
    UNIQUE (building_id, subscription_id)
);

CREATE TABLE sessions (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    ssid VARCHAR(64) NOT NULL
);

CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(32) UNIQUE NOT NULL
);

CREATE TABLE users_roles (
    id SERIAL PRIMARY KEY,
    role_id INT NOT NULL REFERENCES roles(id) ON UPDATE CASCADE ON DELETE CASCADE,
    user_id INT NOT NULL REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE FUNCTION assign_default_role_to_new_user() RETURNS TRIGGER LANGUAGE 'plpgsql' AS $$
BEGIN
    INSERT INTO users_roles (role_id, user_id) VALUES ((SELECT id FROM roles WHERE name = 'regular'), NEW.id);
    RETURN NEW;
END;
$$;

CREATE TRIGGER assign_default_role_to_new_users AFTER INSERT ON users FOR EACH ROW EXECUTE PROCEDURE assign_default_role_to_new_user();

INSERT INTO subscriptions
    (name)
VALUES
    ('fuels-email'),
    ('fuels-notification'),
    ('modernization-email'),
    ('modernization-notification'),
    ('solar-panels-email'),
    ('solar-panels-notification');

INSERT INTO modernizations
    (id, name, label)
VALUES
    (1, 'roof-isolation', 'Ocieplenie dachu/stropu'),
    (2, 'wall-isolation', 'Ocieplenie ścian'),
    (3, 'woodwork-replacement', 'Wymiana stolarki drzwiowej/okiennej');

INSERT INTO heater_types
    (id, name, label)
VALUES
    (1, 'coal-based', 'Kocioł węglowy'),
    (2, 'heat-pump', 'Pompa ciepła'),
    (3, 'heat-network', 'Sieć ciepłownicza'),
    (4, 'gas-based', 'Ogrzewanie gazowe'),
    (5, 'electric-based', 'Ogrzewanie elektryczne'),
    (6, 'pellet-based', 'Kocioł na pellet'),
    (7, 'wood-based', 'Kocioł na drewno'),
    (8, 'fireplace', 'Kominek');

INSERT INTO fuels
    (id, name, label, unit)
VALUES
    (1, 'lump-wood', 'Drewno kawałkowe', 'kg'),
    (2, 'nut-coal', 'Węgiel orzech', 'kg'),
    (3, 'pellet', 'Pellet', 'kg'),
    (4, 'natural-gas', 'Gaz ziemny', 'm³'),
    (5, 'gas-tanks', 'Gaz butla', 'szt'),
    (6, 'lignite', 'Węgiel brunatny', 'kg'),
    (7, 'oil', 'Olej opałowy', 'litr');

INSERT INTO thermal_classes
    (id, name, label, eco_project)
VALUES
    (1, 'third', 'Klasa III', false),
    (2, 'fourth', 'Klasa IV', false),
    (3, 'fifth', 'Klasa V', false),
    (4, 'fifth-with-eco', 'Klasa V + Eco', true),
    (5, 'eco', 'Eco', true);

INSERT INTO roles
    (id, name)
VALUES
    (1, 'regular'),
    (2, 'moderator'),
    (3, 'administrator'),
    (4, 'developer');

INSERT INTO users
    (id, name, surname, e_mail, password)
VALUES
    (1, 'asd', 'asd', 'asd@asd.asd', '$2y$10$7Y1N8nmGNxgc0Svu2kwefO8GlxIPTceuyzWqtwCDARGhcnuAougKm');

INSERT INTO users_roles
    (user_id, role_id)
VALUES
    (1, 3);
