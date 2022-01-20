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
    eco_project BOOLEAN NOT NULL,
    fifth_class BOOLEAN NOT NULL
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
    unit VARCHAR(8) NOT NULL,
    caloric_value DECIMAL(7, 3) NOT NULL
);

CREATE TABLE buildings_fuels (
    id SERIAL PRIMARY KEY,
    building_id INT NOT NULL REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE,
    fuel_id INT NOT NULL REFERENCES fuels(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    consumption DECIMAL(7, 3) NOT NULL CHECK (consumption >= 0),
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
    eco_project BOOLEAN NOT NULL,
    fifth_class BOOLEAN NOT NULL,
    priority INT NOT NULL CHECK (priority >= 0)
);

CREATE TABLE emission_indicators (
    id SERIAL PRIMARY KEY,
    co2 INT NOT NULL CHECK (co2 >= 0),
    pm10 INT NOT NULL CHECK (pm10 >= 0),
    pm25 INT NOT NULL CHECK (pm25 >= 0),
    co INT NOT NULL CHECK (co >= 0),
    nox INT NOT NULL CHECK (nox >= 0),
    so2 INT NOT NULL CHECK (so2 >= 0),
    bap INT NOT NULL CHECK (bap >= 0)
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
    (1, 'solid-based', 'Ogrzewanie na paliwo stałe'),
    (2, 'gas-based', 'Ogrzewanie gazowe'),
    (3, 'oil-based', 'Ogrzewanie olejowe'),
    (4, 'electric-heater', 'Ogrzewanie elektryczne'),
    (5, 'heat-network', 'Sieć ciepłownicza'),
    (6, 'heat-pump', 'Pompa ciepła'),
    (8, 'furmance', 'Piec'),
    (9, 'kitchen-stove', 'Piecokuchnia'),
    (10, 'freestanding', 'Piec wolnostojący'),
    (11, 'tiled-stove', 'Piec kaflowy'),
    (12, 'fireplace', 'Kominek');

INSERT INTO fuels
    (id, name, label, unit, caloric_value)
VALUES
    (1, 'nut-coal', 'Węgiel orzech', 't', 22.67),
    (2, 'cube-coal', 'Węgiel kostka', 't', 22.67),
    (3, 'pea-coal', 'Węgiel groszek', 't', 22.67),
    (4, 'coal-dust', 'Węgiel miał', 't', 22.67),
    (5, 'lignite', 'Węgiel brunatny', 't', 8.13),
    (6, 'lump-wood', 'Drewno kawałkowe', 't', 10.92),
    (7, 'pellet', 'Pellet/brykiet', 't', 15.6),
    (8, 'biomass', 'Inna biomasa', 't', 15.6),
    (9, 'natural-gas', 'Gaz przewodowy', 'm³', 0.02782758),
    (10, 'gas-tank', 'Gaz butla', 'litr', 47.3),
    (11, 'oil', 'Olej opałowy', 'litr', 43);

INSERT INTO emission_rules
    (id, eco_project, fifth_class, priority, heater_type_id)
VALUES
    (1, false, false, 0, 12),
    (2, false, false, 0, 8),
    (3, false, false, 0, 9),
    (4, false, false, 0, 10),
    (5, true, false, 1, 12),
    (6, true, false, 1, 8),
    (7, true, false, 1, 9),
    (8, true, false, 1, 10),
    (9, false, false, 0, 11),
    (10, false, false, 0, 1),
    (11, false, false, 1, 1),
    (12, false, false, 2, 1),
    (13, false, true, 3, 1),
    (14, false, true, 4, 1),
    (15, false, true, 5, 1),
    (16, false, false, 6, 1),
    (17, false, false, 0, 2),
    (18, false, false, 0, 3),
    (19, false, false, 0, 6),
    (20, false, false, 0, 5),
    (21, false, false, 0, 4);

INSERT INTO emission_indicators
    (id, co2, pm10, pm25, co, nox, so2, bap)
VALUES
    (1, 94780, 667, 517, 3182, 192, 338, 0.371),
    (2, 0, 798, 756, 5250, 60, 0, 0.13),
    (3, 0, 126, 87, 530, 95, 0, 0.055),
    (4, 0, 23, 22, 916, 122, 0, 0),
    (5, 94780, 383, 297, 2797, 254, 365, 0.301),
    (6, 0, 247, 234, 4200, 80, 0, 0.105),
    (7, 94780, 427, 331, 5040, 170, 560, 0.28),
    (8, 94720, 390, 304, 5365, 91, 331, 0.384),
    (9, 103960, 545, 423, 6095, 196, 660, 0.55),
    (10, 0, 407, 385, 4166, 60, 0, 0.127),
    (11, 94780, 595, 411, 5040, 143, 343, 0.627),
    (12, 94780, 250, 194, 5059, 118, 523, 0.036),
    (13, 0, 241, 229, 5621, 86, 0, 0.19),
    (14, 0, 74, 70, 1667, 131, 6, 0.026),
    (15, 94780, 77, 60, 502, 274, 439, 0.004),
    (16, 94780, 91, 70, 545, 167, 343, 0.004),
    (17, 0, 42, 28, 537, 113, 7, 0.0253),
    (18, 94780, 27, 21, 350, 0, 0, 0.04),
    (19, 0, 26, 25, 323, 0, 0, 0.035),
    (20, 94780, 18, 14, 250, 0, 0, 0.027),
    (21, 0, 16, 11, 232, 0, 0, 0.02),
    (22, 0, 126, 87, 530, 95, 0, 0.055),
    (23, 55410, 0.3, 0.3, 42, 60, 0.4, 0.8),
    (24, 77400, 2, 2, 51, 97, 111, 0.12),
    (25, 216944, 0, 0, 0, 0, 0, 0),
    (26, 95070, 0, 0, 0, 0, 0, 0),
    (27, 110340, 0, 0, 0, 0, 0, 0),
    (28, 216940, 0, 0, 0, 0, 0, 0);

INSERT INTO emission_indicator_rules
    (id, emission_rule_id, emission_indicator_id, fuel_id)
VALUES
    (1, 1, 1, 1),
    (2, 1, 1, 2),
    (3, 1, 1, 3),
    (4, 1, 1, 4),
    (5, 1, 2, 6),
    (6, 1, 3, 7),
    (7, 1, 3, 8),
    (8, 2, 1, 1),
    (9, 2, 1, 2),
    (10, 2, 1, 3),
    (11, 2, 1, 4),
    (12, 2, 2, 6),
    (13, 2, 3, 7),
    (14, 2, 3, 8),
    (15, 3, 1, 1),
    (16, 3, 1, 2),
    (17, 3, 1, 3),
    (18, 3, 1, 4),
    (19, 3, 2, 6),
    (20, 3, 3, 7),
    (21, 3, 3, 8),
    (22, 4, 1, 1),
    (23, 4, 1, 2),
    (24, 4, 1, 3),
    (25, 4, 1, 4),
    (26, 4, 2, 6),
    (27, 4, 3, 7),
    (28, 4, 3, 8),
    (29, 5, 4, 6),
    (30, 6, 4, 6),
    (31, 7, 4, 6),
    (32, 8, 4, 6),
    (33, 9, 5, 1),
    (34, 9, 5, 2),
    (35, 9, 5, 3),
    (36, 9, 5, 4),
    (37, 9, 6, 6),
    (38, 10, 7, 1),
    (39, 10, 7, 2),
    (40, 10, 7, 3),
    (41, 10, 8, 4),
    (42, 10, 9, 5),
    (43, 10, 10, 6),
    (44, 11, 11, 1),
    (45, 11, 11, 2),
    (46, 11, 11, 3),
    (47, 11, 12, 4),
    (48, 11, 13, 6),
    (49, 11, 14, 7),
    (50, 12, 15, 3),
    (51, 12, 16, 4),
    (52, 12, 17, 7),
    (53, 13, 18, 3),
    (54, 13, 19, 6),
    (55, 14, 18, 3),
    (56, 14, 19, 6),
    (57, 15, 20, 3),
    (58, 15, 21, 7),
    (59, 16, 22, 7),
    (60, 17, 23, 9),
    (61, 18, 24, 11),
    (63, 20, 26, 1),
    (64, 20, 26, 2),
    (65, 20, 26, 3),
    (66, 20, 26, 4),
    (67, 20, 27, 5);

INSERT INTO thermal_classes
    (id, name, label, eco_project, fifth_class)
VALUES
    (1, 'third', 'Klasa III', false, false),
    (2, 'fourth', 'Klasa IV', false, false),
    (3, 'fifth', 'Klasa V', false, true),
    (4, 'fifth-with-eco', 'Klasa V + Eco', true, true),
    (5, 'eco', 'Eco', true, false);

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
