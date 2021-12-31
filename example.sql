INSERT INTO users
    (id, name, surname, e_mail, password)
VALUES
    (1, 'asd', 'asd', 'asd@asd.asd', '$2y$10$7Y1N8nmGNxgc0Svu2kwefO8GlxIPTceuyzWqtwCDARGhcnuAougKm');

INSERT INTO addresses
    (id, country, district, community, location, street, building_no, apartment_no)
VALUES
    (1, 'Polska', 'Powiat', 'Gmina', 'Miejscowość', 'Ulica', '1A', '5'),
    (2, 'Polska', 'Powiat', 'Inna Gmina', 'Inna Miejscowość', 'Inna Ulica', '5', '');

INSERT INTO distributors
    (id, company_name, address_id)
VALUES
    (1, 'company-A', 1);

INSERT INTO distributors_fuels
    (id, distributor_id, fuel_id)
VALUES
    (1, 1, 1);

INSERT INTO details
    (id, area, storeys, housemates, water_usage, energy_usage, destination)
VALUES
    (1, 50, 2, 3, 'standard', 'standard', 'residential');

INSERT INTO buildings
    (id, user_id, address_id, details_id)
VALUES
    (1, 1, 2, 1);

INSERT INTO heaters
    (id, building_id, heater_type_id, power, combustion_chamber, efficiency, installation_year, production_year, data_source, dust_extractor, thermal_class_id)
VALUES
    (1, 1, 1, 0, 'closed', 100, 2021, 2020, 'Tabliczka znamionowa', true, 1);
