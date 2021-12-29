DROP SCHEMA public CASCADE;
CREATE SCHEMA public;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    surname VARCHAR(64) NOT NULL,
    e_mail VARCHAR(128) NOT NULL,
    password VARCHAR(80) NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT false
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
CREATE TYPE data_sources AS ENUM ('tabliczka znamionowa', 'dokumentacja techniczna', 'wiedza właściciela');
CREATE TYPE fuel_providers AS ENUM ('manual', 'automat', 'none');

CREATE TABLE dust_extractors (
    id SERIAL PRIMARY KEY,
    heater_id INT NOT NULL UNIQUE,
    efficiency DECIMAL(7, 3) NOT NULL
);

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

CREATE TABLE heater_classes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    eco_project BOOLEAN NOT NULL
);

CREATE TABLE heaters (
    id SERIAL PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    heater_type_id INT NOT NULL REFERENCES heater_types(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    power DECIMAL(7, 3) NOT NULL,
    combustion_chamber combustion_chambers NOT NULL,
    efficiency DECIMAL(7, 3) NOT NULL,
    installation_year INT NOT NULL,
    production_year INT NOT NULL,
    data_source data_sources NOT NULL,
    fuel_provider fuel_providers NOT NULL,
    heater_class_id INT NOT NULL REFERENCES heater_classes ON UPDATE CASCADE ON DELETE RESTRICT
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
    fuel_provider fuel_providers NOT NULL,
    heater_class INT NOT NULL REFERENCES heater_classes(id) ON UPDATE CASCADE ON DELETE RESTRICT,
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

INSERT INTO users
    (id, name, surname, e_mail, password)
VALUES
    (1, 'asd', 'asd', 'asd@asd.asd', '$2y$10$7Y1N8nmGNxgc0Svu2kwefO8GlxIPTceuyzWqtwCDARGhcnuAougKm');

INSERT INTO modernizations
    (id, name, label)
VALUES
    (1, 'roof-isolation', 'Ocieplenie dachu/stropu'),
    (2, 'wall-isolation', 'Ocieplenie ścian'),
    (3, 'woodwork-replacement', 'Wymiana stolarki drzwiowej/okiennej');

INSERT INTO heater_types
    (id, name, label)
VALUES
    (1, 'solar-panels', 'Kolektory słoneczne'),
    (2, 'pump', 'Pompa ciepła'),
    (3, 'heat-network', 'Sieć ciepłownicza'),
    (4, 'gas-heater', 'Ogrzewanie gazowe'),
    (5, 'electric-heater', 'Ogrzewanie elektryczne'),
    (6, 'pellet-heater', 'Kocioł na pellet');

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

INSERT INTO addresses
    (id, country, district, community, location, street, building_no, apartment_no)
VALUES
    (1, 'Polska', 'Powiat', 'Gmina', 'Miejscowość', 'Ulica', '1A', '5');

INSERT INTO distributors
    (id, company_name, address_id)
VALUES
    (1, 'company-A', 1);

INSERT INTO distributors_fuels
    (id, distributor_id, fuel_id)
VALUES
    (1, 1, 1);
