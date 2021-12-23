CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    surname VARCHAR(64) NOT NULL,
    e_mail VARCHAR(128) NOT NULL,
    password VARCHAR(80) NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT false
);

CREATE TYPE building_destinations AS ENUM ('residential', 'service', 'residential-n-service', 'industrial');
CREATE TYPE consumptions AS ENUM ('little', 'standard', 'noticeable');

CREATE TABLE IF NOT EXISTS buildings (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    address_id INT NOT NULL UNIQUE REFERENCES addresses(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    area DECIMAL(7, 3) NOT NULL,
    storeys INT NOT NULL,
    housemates INT NOT NULL,
    water_usage consumptions NOT NULL,
    energy_usage consumptions NOT NULL,
    destination building_destinations NOT NULL
);

CREATE TABLE IF NOT EXISTS addresses (
    id SERIAL PRIMARY KEY,
    country VARCHAR(64) NOT NULL,
    district VARCHAR(64) NOT NULL,
    community VARCHAR(64) NOT NULL,
    location VARCHAR(64) NOT NULL,
    street VARCHAR(64) NOT NULL,
    building_no VARCHAR(16) NOT NULL,
    apartment_no VARCHAR(16) NOT NULL
);

CREATE TABLE IF NOT EXISTS modernizations (
    id SERIAL PRIMARY KEY,
    name VARCHAR(32) NOT NULL
);

CREATE TABLE IF NOT EXISTS buildings_modernizations (
    id INT PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    modernization_id INT NOT NULL REFERENCES modernizations(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TYPE combustion_chambers AS ENUM ('open', 'closed', 'none');
CREATE TYPE data_sources AS ENUM ('tabliczka znamionowa', 'dokumentacja techniczna', 'wiedza właściciela');
CREATE TYPE fuel_providers AS ENUM ('manual', 'automat', 'none');

CREATE TABLE IF NOT EXISTS dust_extractors (
    id SERIAL PRIMARY KEY,
    heater_id INT NOT NULL UNIQUE,
    efficiency DECIMAL(7, 3) NOT NULL
);

CREATE TABLE IF NOT EXISTS heater_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(64) NOT NULL
);

CREATE TABLE IF NOT EXISTS heater_classes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    eco_project BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS heaters (
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

CREATE TABLE IF NOT EXISTS fuels (
    id SERIAL PRIMARY KEY,
    name VARCHAR(32) UNIQUE NOT NULL,
    unit VARCHAR(8) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS buildings_fuels (
    id SERIAL PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    fuel_id INT NOT NULL REFERENCES fuels(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS emission_rules (
    id SERIAL PRIMARY KEY,
    heater_type INT NOT NULL REFERENCES heater_types(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    fuel_provider fuel_providers NOT NULL,
    heater_class INT NOT NULL REFERENCES heater_classes(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    priority INT NOT NULL CHECK (priority > 0)
);

CREATE TABLE IF NOT EXISTS emission_indicators (
    id SERIAL PRIMARY KEY,
    co2 INT NOT NULL CHECK (co2 > 0),
    pm10 INT NOT NULL CHECK (pm10 > 0),
    pm25 INT NOT NULL CHECK (pm25 > 0),
    co INT NOT NULL CHECK (co > 0),
    nox INT NOT NULL CHECK (nox > 0),
    so2 INT NOT NULL CHECK (so2 > 0),
    bap INT NOT NULL CHECK (bap > 0)
);

CREATE TABLE IF NOT EXISTS emission_indicator_rules (
    id SERIAL PRIMARY KEY,
    fuel_id INT NOT NULL REFERENCES fuels ON UPDATE CASCADE ON DELETE CASCADE,
    emission_rule_id INT NOT NULL REFERENCES emission_rules(id) ON UPDATE CASCADE ON DELETE CASCADE,
    emission_indicator_id INT NOT NULL REFERENCES emission_indicators(id) ON UPDATE CASCADE ON DELETE RESTRICT
);