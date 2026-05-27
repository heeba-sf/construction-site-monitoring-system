-- ============================================================
-- DATABASE CREATION
-- ============================================================
DROP DATABASE IF EXISTS construction_management;
CREATE DATABASE construction_management;
USE construction_management;

-- ============================================================
-- TABLE CREATION
-- ============================================================

-- Client table
CREATE TABLE Client (
    Client_id INT NOT NULL,
    First_name VARCHAR(20),
    Last_name VARCHAR(20),
    Address VARCHAR(30),
    PRIMARY KEY (Client_id)
);

-- SD table
CREATE TABLE SD (
    SD_id INT NOT NULL,
    date_creation DATE,
    Version VARCHAR(20),
    PRIMARY KEY (SD_id)
);

-- Project table
CREATE TABLE Project (
    Project_id INT NOT NULL,
    Client_id INT NOT NULL,
    SD_id INT,
    Project_name VARCHAR(20),
    Budget DECIMAL(10,3),
    Project_start_date DATE,
    Project_expected_end_date DATE,
    PRIMARY KEY (Project_id),
    FOREIGN KEY (Client_id) REFERENCES Client(Client_id),
    FOREIGN KEY (SD_id) REFERENCES SD(SD_id)
);

-- Work P table
CREATE TABLE Work_P (
    WorkP_id INT NOT NULL,
    SD_id INT NOT NULL,
    WorkP_name VARCHAR(20),
    Expected_quantity INT,
    Measurment_unit VARCHAR(10),
    Unit_price DECIMAL(10,3),
    PRIMARY KEY (WorkP_id),
    FOREIGN KEY (SD_id) REFERENCES SD(SD_id)
);

-- Situation table
CREATE TABLE Situation (
    Situation_id INT NOT NULL,
    Project_id INT NOT NULL,
    Comments VARCHAR(100),
    Start_date DATE,
    End_date DATE,
    PRIMARY KEY (Situation_id),
    FOREIGN KEY (Project_id) REFERENCES Project(Project_id)
);

-- Achieve table
CREATE TABLE Achieve (
    Situation_id INT NOT NULL,
    WorkP_id INT NOT NULL,
    Achieved_quantity INT,
    PRIMARY KEY (Situation_id, WorkP_id),
    FOREIGN KEY (Situation_id) REFERENCES Situation(Situation_id),
    FOREIGN KEY (WorkP_id) REFERENCES Work_P(WorkP_id)
);

-- Document table
CREATE TABLE Document (
    Document_id INT NOT NULL,
    Project_id INT NOT NULL,
    Document_type VARCHAR(20),
    Document_url VARCHAR(255),
    Upload_date DATE,
    Authorization_level VARCHAR(20),
    PRIMARY KEY (Document_id),
    FOREIGN KEY (Project_id) REFERENCES Project(Project_id)
);

-- ============================================================
-- CLIENT
-- ============================================================
INSERT INTO Client VALUES (1, 'Ahmed', 'Ben Salah', '12 Carthage Street, Tunis');
INSERT INTO Client VALUES (2, 'Fatma', 'Trabelsi', '45 Bourguiba Avenue, Sfax');
INSERT INTO Client VALUES (3, 'Karim', 'Gharbi', '8 Ibn Khaldoun Street, Sousse');
INSERT INTO Client VALUES (4, 'Leila', 'Chaabane', '22 Republic Street, Nabeul');
INSERT INTO Client VALUES (5, 'Amine', 'Missaoui', '67 Environment Avenue, Bizerte');
INSERT INTO Client VALUES (6, 'Sonia', 'Khelifi', '3 Tahar Haddad Street, Monastir');
INSERT INTO Client VALUES (7, 'Riadh', 'Jebali', '15 Roses Street, Gabes');
INSERT INTO Client VALUES (8, 'Nadia', 'Bouaziz', '9 Tunis Avenue, Kairouan');
INSERT INTO Client VALUES (9, 'Hassen', 'Dridi', '31 Farhat Hached Street, Ariana');
INSERT INTO Client VALUES (10, 'Slim', 'Zouari', '77 Bechir Sfar Avenue, Sfax');

-- ============================================================
-- SD
-- ============================================================
INSERT INTO SD VALUES (1, '2023-01-10', 'v1.0');
INSERT INTO SD VALUES (2, '2023-06-01', 'v2.1');
INSERT INTO SD VALUES (3, '2024-03-15', 'v1.0');
INSERT INTO SD VALUES (4, '2024-09-20', 'v1.2');
INSERT INTO SD VALUES (5, '2025-02-10', 'v1.0');
INSERT INTO SD VALUES (6, '2025-08-05', 'v2.0');
INSERT INTO SD VALUES (7, '2026-01-12', 'v1.0');
INSERT INTO SD VALUES (8, '2026-03-01', 'v1.1');
INSERT INTO SD VALUES (9, '2023-12-01', 'v1.0');
INSERT INTO SD VALUES (10, '2023-05-01', 'v1.0');
INSERT INTO SD VALUES (11, '2024-05-15', 'v1.1');
INSERT INTO SD VALUES (12, '2024-12-01', 'v1.0');
INSERT INTO SD VALUES (13, '2026-01-20', 'v1.0');
INSERT INTO SD VALUES (14, '2025-04-10', 'v1.2');
INSERT INTO SD VALUES (15, '2025-11-01', 'v1.0');

-- ============================================================
-- PROJECT
-- ============================================================
INSERT INTO Project VALUES (1, 1, 1, 'Carthage Residency', 1250000.000, '2023-02-01', '2024-06-30');
INSERT INTO Project VALUES (2, 2, 2, 'Sousse Beach Hotel', 1850000.000, '2023-07-01', '2025-06-30');
INSERT INTO Project VALUES (3, 3, 3, 'Bizerte Complex', 975000.000, '2024-04-01', '2026-03-31');
INSERT INTO Project VALUES (4, 4, 4, 'Ariana Tech Park', 780000.000, '2024-10-01', '2025-09-30');
INSERT INTO Project VALUES (5, 5, 5, 'Hammamet Clinic', 1100000.000, '2025-03-01', '2027-02-28');
INSERT INTO Project VALUES (6, 6, 6, 'Sfax Warehouse', 410000.000, '2025-09-01', '2026-12-31');
INSERT INTO Project VALUES (7, 7, 7, 'Tunis Lake Tower', 1950000.000, '2026-02-01', '2028-01-31');
INSERT INTO Project VALUES (8, 8, 8, 'Kairouan School', 310000.000, '2026-03-15', '2027-06-30');
INSERT INTO Project VALUES (9, 9, 9, 'Monastir Parking', 155000.000, '2026-01-20', '2027-01-19');
INSERT INTO Project VALUES (10, 10, 10, 'Nabeul North Villa', 480000.000, '2025-05-01', '2026-08-31');
INSERT INTO Project VALUES (11, 1, 11, 'Carthage Annex B', 390000.000, '2024-01-01', '2024-12-31');
INSERT INTO Project VALUES (12, 2, 12, 'Sousse Swimming Pool', 220000.000, '2023-06-01', '2024-05-31');
INSERT INTO Project VALUES (13, 3, 13, 'Industrial Zone Exp.', 850000.000, '2024-06-01', '2026-05-31');
INSERT INTO Project VALUES (14, 5, 14, 'Hammamet Apartment', 630000.000, '2025-01-01', '2025-12-31');
INSERT INTO Project VALUES (15, 6, 15, 'Monastir Event Hall', 180000.000, '2026-02-01', '2026-11-30');

-- ============================================================
-- WORK P
-- ============================================================
-- SD 1 - Carthage Residency
INSERT INTO Work_P VALUES (1, 1, 'Earthworks', 500, 'm3', 45.000);
INSERT INTO Work_P VALUES (2, 1, 'Reinforced Con.', 800, 'm3', 320.000);
INSERT INTO Work_P VALUES (3, 1, 'Masonry', 2400, 'm2', 85.000);
INSERT INTO Work_P VALUES (4, 1, 'Facade Coating', 1800, 'm2', 38.000);
-- SD 2 - Sousse Beach Hotel
INSERT INTO Work_P VALUES (5, 2, 'Foundations', 700, 'm3', 290.000);
INSERT INTO Work_P VALUES (6, 2, 'RC Structure', 1100, 'm3', 315.000);
INSERT INTO Work_P VALUES (7, 2, 'Alu. Joinery', 480, 'ml', 210.000);
INSERT INTO Work_P VALUES (8, 2, 'Interior Paint', 4500, 'm2', 18.000);
-- SD 3 - Bizerte Complex
INSERT INTO Work_P VALUES (9, 3, 'Earthworks', 400, 'm3', 44.000);
INSERT INTO Work_P VALUES (10, 3, 'Slab Concrete', 560, 'm3', 300.000);
INSERT INTO Work_P VALUES (11, 3, 'Roof Cover', 900, 'm2', 95.000);
-- SD 4 - Ariana Tech Park
INSERT INTO Work_P VALUES (12, 4, 'Earthworks', 900, 'm3', 46.000);
INSERT INTO Work_P VALUES (13, 4, 'Reinforced Con.', 1200, 'm3', 325.000);
INSERT INTO Work_P VALUES (14, 4, 'Cable Networks', 1, 'lumpsum', 72000.000);
-- SD 5 - Hammamet Clinic
INSERT INTO Work_P VALUES (15, 5, 'Foundations', 750, 'm3', 295.000);
INSERT INTO Work_P VALUES (16, 5, 'RC Structure', 3200, 'm2', 210.000);
INSERT INTO Work_P VALUES (17, 5, 'Medical Fluids', 1, 'lumpsum', 145000.000);
INSERT INTO Work_P VALUES (18, 5, 'Plasterboard', 4000, 'm2', 40.000);
-- SD 6 - Sfax Warehouse
INSERT INTO Work_P VALUES (19, 6, 'Concrete Slab', 2000, 'm2', 95.000);
INSERT INTO Work_P VALUES (20, 6, 'Steel Frame', 45, 'tonne', 2100.000);
INSERT INTO Work_P VALUES (21, 6, 'Sheet Roofing', 2000, 'm2', 55.000);
-- SD 7 - Tunis Lake Tower
INSERT INTO Work_P VALUES (22, 7, 'Bored Piles', 200, 'unit', 1800.000);
INSERT INTO Work_P VALUES (23, 7, 'Raft Foundation', 1800, 'm2', 280.000);
INSERT INTO Work_P VALUES (24, 7, 'RC Shear Walls', 4500, 'm2', 195.000);
-- SD 8 - Kairouan School
INSERT INTO Work_P VALUES (25, 8, 'Shell Works', 1, 'lumpsum', 120000.000);
INSERT INTO Work_P VALUES (26, 8, 'Finishing Works', 1, 'lumpsum', 85000.000);
INSERT INTO Work_P VALUES (27, 8, 'Green Spaces', 600, 'm2', 25.000);
-- SD 9 - Monastir Parking
INSERT INTO Work_P VALUES (28, 9, 'Bored Piles', 120, 'unit', 1800.000);
INSERT INTO Work_P VALUES (29, 9, 'Parking Raft', 800, 'm2', 260.000);
INSERT INTO Work_P VALUES (30, 9, 'RC Shear Walls', 1200, 'm2', 185.000);
-- SD 10 - Nabeul North Villa
INSERT INTO Work_P VALUES (31, 10, 'Shell Works', 1, 'lumpsum', 180000.000);
INSERT INTO Work_P VALUES (32, 10, 'Floor Tiling', 450, 'm2', 60.000);
INSERT INTO Work_P VALUES (33, 10, 'Wood Joinery', 22, 'unit', 1200.000);
-- SD 11 - Carthage Annex B
INSERT INTO Work_P VALUES (34, 11, 'Earthworks', 200, 'm3', 44.000);
INSERT INTO Work_P VALUES (35, 11, 'Reinforced Con.', 350, 'm3', 310.000);
INSERT INTO Work_P VALUES (36, 11, 'Masonry', 900, 'm2', 80.000);
-- SD 12 - Sousse Swimming Pool
INSERT INTO Work_P VALUES (37, 12, 'Excavation', 180, 'm3', 50.000);
INSERT INTO Work_P VALUES (38, 12, 'RC Walls Floor', 420, 'm2', 195.000);
INSERT INTO Work_P VALUES (39, 12, 'Waterproofing', 420, 'm2', 65.000);
-- SD 13 - Industrial Zone Exp.
INSERT INTO Work_P VALUES (40, 13, 'Earthworks', 1200, 'm3', 46.000);
INSERT INTO Work_P VALUES (41, 13, 'Industrial Slab', 3500, 'm2', 110.000);
INSERT INTO Work_P VALUES (42, 13, 'Steel Frame', 80, 'tonne', 2100.000);
INSERT INTO Work_P VALUES (43, 13, 'Utility Networks', 1, 'lumpsum', 95000.000);
-- SD 14 - Hammamet Apartment
INSERT INTO Work_P VALUES (44, 14, 'Infrastructure', 1, 'lumpsum', 160000.000);
INSERT INTO Work_P VALUES (45, 14, 'Superstructure', 1, 'lumpsum', 280000.000);
INSERT INTO Work_P VALUES (46, 14, 'Interior Walls', 1800, 'm2', 35.000);
-- SD 15 - Monastir Event Hall
INSERT INTO Work_P VALUES (47, 15, 'Shell Works', 1, 'lumpsum', 90000.000);
INSERT INTO Work_P VALUES (48, 15, 'Finishing Works', 1, 'lumpsum', 55000.000);
INSERT INTO Work_P VALUES (49, 15, 'Interior Paint', 1200, 'm2', 15.000);

-- ============================================================
-- DOCUMENT
-- ============================================================
INSERT INTO Document VALUES (1, 1, 'Contract', '/docs/p1/contract.pdf', '2023-01-25', 'Confidential');
INSERT INTO Document VALUES (2, 1, 'Plan', '/docs/p1/site_plan.pdf', '2023-02-05', 'Restricted');
INSERT INTO Document VALUES (3, 1, 'Quote', '/docs/p1/quote.pdf', '2023-02-10', 'Restricted');
INSERT INTO Document VALUES (4, 1, 'QC Sheet', '/docs/p1/qc_sheet.pdf', '2023-08-01', 'Public');
INSERT INTO Document VALUES (5, 1, 'Approval', '/docs/p1/approval.pdf', '2023-09-01', 'Confidential');
INSERT INTO Document VALUES (6, 2, 'Contract', '/docs/p2/contract.pdf', '2023-06-15', 'Confidential');
INSERT INTO Document VALUES (7, 2, 'Plan', '/docs/p2/floor_plan.pdf', '2023-07-03', 'Restricted');
INSERT INTO Document VALUES (8, 2, 'Quote', '/docs/p2/quote.pdf', '2023-07-10', 'Restricted');
INSERT INTO Document VALUES (9, 2, 'QC Sheet', '/docs/p2/qc_sheet.pdf', '2024-01-10', 'Public');
INSERT INTO Document VALUES (10, 3, 'Contract', '/docs/p3/contract.pdf', '2024-03-20', 'Confidential');
INSERT INTO Document VALUES (11, 3, 'Plan', '/docs/p3/site_plan.pdf', '2024-04-02', 'Restricted');
INSERT INTO Document VALUES (12, 4, 'Contract', '/docs/p4/contract.pdf', '2024-09-25', 'Confidential');
INSERT INTO Document VALUES (13, 4, 'Approval', '/docs/p4/approval.pdf', '2024-10-05', 'Restricted');
INSERT INTO Document VALUES (14, 4, 'Plan', '/docs/p4/layout_plan.pdf', '2024-10-10', 'Restricted');
INSERT INTO Document VALUES (15, 5, 'Contract', '/docs/p5/contract.pdf', '2025-02-20', 'Confidential');
INSERT INTO Document VALUES (16, 5, 'Plan', '/docs/p5/clinic_plan.pdf', '2025-03-05', 'Restricted');
INSERT INTO Document VALUES (17, 6, 'Contract', '/docs/p6/contract.pdf', '2025-08-10', 'Confidential');
INSERT INTO Document VALUES (18, 7, 'Contract', '/docs/p7/contract.pdf', '2026-01-15', 'Confidential');
INSERT INTO Document VALUES (19, 7, 'Plan', '/docs/p7/tower_plan.pdf', '2026-02-03', 'Restricted');
INSERT INTO Document VALUES (20, 7, 'Quote', '/docs/p7/quote.pdf', '2026-02-08', 'Restricted');
INSERT INTO Document VALUES (21, 8, 'Contract', '/docs/p8/contract.pdf', '2026-03-01', 'Confidential');
INSERT INTO Document VALUES (22, 8, 'Plan', '/docs/p8/school_plan.pdf', '2026-03-18', 'Public');
INSERT INTO Document VALUES (23, 9, 'Contract', '/docs/p9/contract.pdf', '2026-01-10', 'Confidential');
INSERT INTO Document VALUES (24, 10, 'Contract', '/docs/p10/contract.pdf', '2025-04-20', 'Confidential');
INSERT INTO Document VALUES (25, 10, 'QC Sheet', '/docs/p10/qc_sheet.pdf', '2025-09-10', 'Public');
INSERT INTO Document VALUES (26, 11, 'Contract', '/docs/p11/contract.pdf', '2023-12-20', 'Confidential');
INSERT INTO Document VALUES (27, 12, 'Contract', '/docs/p12/contract.pdf', '2023-05-15', 'Confidential');
INSERT INTO Document VALUES (28, 12, 'Plan', '/docs/p12/pool_plan.pdf', '2023-06-01', 'Restricted');
INSERT INTO Document VALUES (29, 13, 'Contract', '/docs/p13/contract.pdf', '2024-05-20', 'Confidential');
INSERT INTO Document VALUES (30, 14, 'Contract', '/docs/p14/contract.pdf', '2024-12-15', 'Confidential');
INSERT INTO Document VALUES (31, 15, 'Contract', '/docs/p15/contract.pdf', '2026-01-25', 'Confidential');

-- ============================================================
-- SITUATION
-- ============================================================
-- Project 1 - Carthage Residency
INSERT INTO Situation VALUES (1, 1, 'Earthworks and foundations', '2023-02-01', '2023-02-28');
INSERT INTO Situation VALUES (2, 1, 'Reinforced concrete 60%', '2023-03-01', '2023-03-31');
INSERT INTO Situation VALUES (3, 1, 'Masonry floors R+1 and R+2', '2023-06-01', '2023-06-30');
INSERT INTO Situation VALUES (4, 1, 'Coating and finishing works', '2023-10-01', '2023-10-31');
-- Project 2 - Sousse Beach Hotel
INSERT INTO Situation VALUES (5, 2, 'Foundations completed', '2023-07-01', '2023-07-31');
INSERT INTO Situation VALUES (6, 2, 'RC structure floors 1 and 2', '2023-09-01', '2023-09-30');
INSERT INTO Situation VALUES (7, 2, 'Joinery and painting start', '2024-01-01', '2024-01-31');
INSERT INTO Situation VALUES (8, 2, 'Interior painting completed', '2024-04-01', '2024-04-30');
-- Project 3 - Bizerte Complex
INSERT INTO Situation VALUES (9, 3, 'General earthworks', '2024-04-01', '2024-04-30');
INSERT INTO Situation VALUES (10, 3, 'Concrete slab 50% progress', '2024-07-01', '2024-07-31');
INSERT INTO Situation VALUES (11, 3, 'Roof covering completed', '2025-01-01', '2025-01-31');
-- Project 4 - Ariana Tech Park
INSERT INTO Situation VALUES (12, 4, 'Earthworks and concrete start', '2024-10-01', '2024-10-31');
INSERT INTO Situation VALUES (13, 4, 'RC structure and networks', '2025-02-01', '2025-02-28');
INSERT INTO Situation VALUES (14, 4, 'Finishing and handover', '2025-07-01', '2025-07-31');
-- Project 5 - Hammamet Clinic
INSERT INTO Situation VALUES (15, 5, 'Foundation phase 1', '2025-03-01', '2025-03-31');
INSERT INTO Situation VALUES (16, 5, 'RC structure ground floor', '2025-06-01', '2025-06-30');
INSERT INTO Situation VALUES (17, 5, 'Partitions and fluids start', '2025-10-01', '2025-10-31');
-- Project 6 - Sfax Warehouse
INSERT INTO Situation VALUES (18, 6, 'Concrete slab 1000 sqm poured', '2025-09-01', '2025-09-30');
INSERT INTO Situation VALUES (19, 6, 'Steel frame and roof covering', '2025-12-01', '2025-12-31');
-- Project 7 - Tunis Lake Tower
INSERT INTO Situation VALUES (20, 7, 'Bored piles 80 units done', '2026-02-01', '2026-02-28');
INSERT INTO Situation VALUES (21, 7, 'Raft foundation started', '2026-03-01', '2026-03-31');
-- Project 8 - Kairouan School
INSERT INTO Situation VALUES (22, 8, 'Shell works site kickoff', '2026-03-15', '2026-04-15');
-- Project 9 - Monastir Parking
INSERT INTO Situation VALUES (23, 9, 'Piles and raft slab', '2026-01-20', '2026-02-20');
INSERT INTO Situation VALUES (24, 9, 'RC shear walls level 1', '2026-03-01', '2026-03-31');
-- Project 10 - Nabeul North Villa
INSERT INTO Situation VALUES (25, 10, 'Foundations and shell works', '2025-05-01', '2025-05-31');
INSERT INTO Situation VALUES (26, 10, 'Structure and finishing works', '2025-09-01', '2025-09-30');
-- Project 11 - Carthage Annex B
INSERT INTO Situation VALUES (27, 11, 'Earthworks and foundations', '2024-01-01', '2024-01-31');
INSERT INTO Situation VALUES (28, 11, 'Masonry and finishing works', '2024-06-01', '2024-06-30');
-- Project 12 - Sousse Swimming Pool
INSERT INTO Situation VALUES (29, 12, 'Excavation and foundations', '2023-06-01', '2023-06-30');
INSERT INTO Situation VALUES (30, 12, 'RC walls and pool floor', '2023-09-01', '2023-09-30');
INSERT INTO Situation VALUES (31, 12, 'Waterproofing and finishing', '2024-03-01', '2024-03-31');
-- Project 13 - Industrial Zone Exp.
INSERT INTO Situation VALUES (32, 13, 'Earthworks phase 1', '2024-06-01', '2024-06-30');
INSERT INTO Situation VALUES (33, 13, 'Industrial slab and structure', '2024-10-01', '2024-10-31');
INSERT INTO Situation VALUES (34, 13, 'Steel frame and utility nets', '2025-04-01', '2025-04-30');
-- Project 14 - Hammamet Apartment
INSERT INTO Situation VALUES (35, 14, 'Infrastructure completed', '2025-01-01', '2025-03-31');
INSERT INTO Situation VALUES (36, 14, 'Superstructure and finishing', '2025-06-01', '2025-08-31');
-- Project 15 - Monastir Event Hall
INSERT INTO Situation VALUES (37, 15, 'Shell works site kickoff', '2026-02-01', '2026-03-31');

-- ============================================================
-- ACHIEVE
-- ============================================================
-- Project 1 (SD1: WorkP 1-4)
INSERT INTO Achieve VALUES (1, 1, 280);
INSERT INTO Achieve VALUES (1, 2, 150);
INSERT INTO Achieve VALUES (2, 1, 500);
INSERT INTO Achieve VALUES (2, 2, 550);
INSERT INTO Achieve VALUES (2, 3, 600);
INSERT INTO Achieve VALUES (3, 2, 800);
INSERT INTO Achieve VALUES (3, 3, 2400);
INSERT INTO Achieve VALUES (3, 4, 900);
INSERT INTO Achieve VALUES (4, 3, 2400);
INSERT INTO Achieve VALUES (4, 4, 1800);
-- Project 2 (SD2: WorkP 5-8)
INSERT INTO Achieve VALUES (5, 5, 400);
INSERT INTO Achieve VALUES (5, 6, 200);
INSERT INTO Achieve VALUES (6, 5, 700);
INSERT INTO Achieve VALUES (6, 6, 800);
INSERT INTO Achieve VALUES (7, 6, 1100);
INSERT INTO Achieve VALUES (7, 7, 300);
INSERT INTO Achieve VALUES (7, 8, 1000);
INSERT INTO Achieve VALUES (8, 7, 480);
INSERT INTO Achieve VALUES (8, 8, 4500);
-- Project 3 (SD3: WorkP 9-11)
INSERT INTO Achieve VALUES (9, 9, 250);
INSERT INTO Achieve VALUES (9, 10, 100);
INSERT INTO Achieve VALUES (10, 9, 400);
INSERT INTO Achieve VALUES (10, 10, 380);
INSERT INTO Achieve VALUES (11, 10, 560);
INSERT INTO Achieve VALUES (11, 11, 900);
-- Project 4 (SD4: WorkP 12-14)
INSERT INTO Achieve VALUES (12, 12, 100);
INSERT INTO Achieve VALUES (12, 13, 50);
INSERT INTO Achieve VALUES (13, 12, 500);
INSERT INTO Achieve VALUES (13, 13, 900);
INSERT INTO Achieve VALUES (13, 14, 1);
INSERT INTO Achieve VALUES (14, 12, 900);
INSERT INTO Achieve VALUES (14, 13, 80);
-- Project 5 (SD5: WorkP 15-18)
INSERT INTO Achieve VALUES (15, 15, 400);
INSERT INTO Achieve VALUES (15, 16, 800);
INSERT INTO Achieve VALUES (16, 15, 750);
INSERT INTO Achieve VALUES (16, 16, 2200);
INSERT INTO Achieve VALUES (17, 16, 3200);
INSERT INTO Achieve VALUES (17, 17, 1);
INSERT INTO Achieve VALUES (17, 18, 1000);
-- Project 6 (SD6: WorkP 19-21)
INSERT INTO Achieve VALUES (18, 19, 1000);
INSERT INTO Achieve VALUES (18, 20, 20);
INSERT INTO Achieve VALUES (19, 19, 2000);
INSERT INTO Achieve VALUES (19, 20, 45);
INSERT INTO Achieve VALUES (19, 21, 1200);
-- Project 7 (SD7: WorkP 22-24)
INSERT INTO Achieve VALUES (20, 22, 80);
INSERT INTO Achieve VALUES (20, 23, 300);
INSERT INTO Achieve VALUES (21, 22, 200);
INSERT INTO Achieve VALUES (21, 23, 900);
INSERT INTO Achieve VALUES (21, 24, 500);
-- Project 8 (SD8: WorkP 25-27)
INSERT INTO Achieve VALUES (22, 25, 1);
-- Project 9 (SD9: WorkP 28-30)
INSERT INTO Achieve VALUES (23, 28, 90);
INSERT INTO Achieve VALUES (23, 29, 400);
INSERT INTO Achieve VALUES (24, 28, 120);
INSERT INTO Achieve VALUES (24, 29, 800);
INSERT INTO Achieve VALUES (24, 30, 600);
-- Project 10 (SD10: WorkP 31-33)
INSERT INTO Achieve VALUES (25, 31, 1);
INSERT INTO Achieve VALUES (25, 32, 200);
INSERT INTO Achieve VALUES (26, 31, 1);
INSERT INTO Achieve VALUES (26, 32, 450);
INSERT INTO Achieve VALUES (26, 33, 22);
-- Project 11 (SD11: WorkP 34-36)
INSERT INTO Achieve VALUES (27, 34, 120);
INSERT INTO Achieve VALUES (27, 35, 180);
INSERT INTO Achieve VALUES (28, 34, 200);
INSERT INTO Achieve VALUES (28, 35, 350);
INSERT INTO Achieve VALUES (28, 36, 900);
-- Project 12 (SD12: WorkP 37-39)
INSERT INTO Achieve VALUES (29, 37, 120);
INSERT INTO Achieve VALUES (29, 38, 500);
INSERT INTO Achieve VALUES (30, 37, 180);
INSERT INTO Achieve VALUES (30, 38, 600);
INSERT INTO Achieve VALUES (31, 37, 180);
INSERT INTO Achieve VALUES (31, 39, 300);
-- Project 13 (SD13: WorkP 40-43)
INSERT INTO Achieve VALUES (32, 40, 600);
INSERT INTO Achieve VALUES (32, 41, 800);
INSERT INTO Achieve VALUES (33, 40, 1200);
INSERT INTO Achieve VALUES (33, 41, 2500);
INSERT INTO Achieve VALUES (33, 42, 40);
INSERT INTO Achieve VALUES (34, 41, 3500);
INSERT INTO Achieve VALUES (34, 42, 80);
INSERT INTO Achieve VALUES (34, 43, 1);
-- Project 14 (SD14: WorkP 44-46)
INSERT INTO Achieve VALUES (35, 44, 1);
INSERT INTO Achieve VALUES (35, 45, 1);
INSERT INTO Achieve VALUES (36, 44, 1);
INSERT INTO Achieve VALUES (36, 45, 1);
INSERT INTO Achieve VALUES (36, 46, 1800);
-- Project 15 (SD15: WorkP 47-49)
INSERT INTO Achieve VALUES (37, 47, 1);