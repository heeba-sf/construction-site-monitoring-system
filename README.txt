==============================================================
   README — Construction Site Monitoring System
   ENSIT — Computer Engineering Department — PFA1 2025-2026
==============================================================

Students    : Heeba Souaf, Rayhane Zarga, Malek Ben Amdouni
Supervisor  : Mrs. Ines Bayoudh Saadi

--------------------------------------------------------------
1. PROJECT DESCRIPTION
--------------------------------------------------------------

This project is a web-based Construction Site Monitoring
System designed for a civil engineering firm. It allows
the firm's internal staff to manage clients, construction
projects, specification documents, work packages, work
progress situations, contractual documents, and financial
indicators through a centralized PHP web interface connected
to a relational MySQL database.

The system includes:
  - A full CRUD web application (PHP, MVC architecture)
  - A MySQL relational database with 7 interconnected tables
  - An AI assistant chatbot powered by a local Ollama model
  - Financial health monitoring with automatic indicators
  - Color-coded progress tracking per work package
  - Auto-generated printable situation reports per project

--------------------------------------------------------------
2. REQUIRED SOFTWARE (PREREQUISITES)
--------------------------------------------------------------

Before installation, make sure the following software is
installed on your machine:

  - XAMPP 8.0 or higher
    Download : https://www.apachefriends.org
    (includes Apache, PHP 7.4+, MySQL 5.7+, phpMyAdmin)

  - A modern web browser
    (Google Chrome, Mozilla Firefox or Microsoft Edge)

  - Ollama (for the integrated AI chatbot)
    Download : https://ollama.com
    (optional — the application works without the chatbot)

--------------------------------------------------------------
3. PROJECT FILE STRUCTURE
--------------------------------------------------------------

PFA1-2026-01/
│
├── Rap_PFA1-2026-01 			   	 ← End of year report								
├── README.txt                      ← This file
├── ConstructManager/
      |
      ├── index.php                  ← Main entry point (router)
      ├── logout.php                 ← Session logout handler
      ├── script.sql                 ← Full database script
      │                                 (table creation + data)
      │
      ├── config/
      │   └── database.php           ← MySQL PDO connection settings
      │
      ├── controllers/               ← Business logic layer (MVC)
      │   ├── ProjectController.php
      │   ├── ClientController.php
      │   ├── WorkController.php
      │   ├── SituationController.php
      │   └── DocumentController.php
      │
      ├── models/                    ← Database query layer
      │   ├── Project.php
      │   ├── Client.php
      │   ├── Work.php
      │   ├── Situation.php
      │   └── Document.php
      │
      ├── views/                     ← User interface layer
      │   ├── layout/
      │   │   ├── header.php         ← Navbar + session check
      │   │   └── footer.php
      │   ├── dashboard.php          ← Home dashboard
      │   ├── project-details.php    ← Project detail page
      │   ├── project-report.php     ← Standalone printable report
      │   ├── login.php              ← Login page
      │   └── ...
      │
      ├── assets/
      │   ├── css/style.css          ← Main stylesheet
      │   └── js/main.js             ← Frontend scripts
      │
      └── uploads/                   ← Uploaded project documents

--------------------------------------------------------------
4. DATABASE INSTALLATION
--------------------------------------------------------------

STEP 1 — Start XAMPP
  1. Open the XAMPP Control Panel
  2. Start the Apache and MySQL services
  3. Wait until both services show a green status indicator

STEP 2 — Create the database
  1. Open a browser and go to:
     http://localhost/phpmyadmin
  2. Click "New" in the left sidebar
  3. Enter the database name: construction_db
  4. Select collation: utf8_general_ci
  5. Click "Create"

STEP 3 — Import the SQL script
  1. Select the "construction_db" database in phpMyAdmin
  2. Click on the "Import" tab
  3. Click "Choose File"
  4. Select the file: script.sql
     (located at the root of the project folder)
  5. Leave the format set to "SQL"
  6. Click "Go" / "Execute"
  7. Verify that the message "Import successful" appears

  The script will automatically create the 7 following tables:
    - Client
    - Project
    - SD              (Specification Document)
    - Work_P          (Work Packages)
    - Situation
    - Achieve         (Achievement records)
    - Document

  It will also insert all necessary test data automatically.

--------------------------------------------------------------
5. WEB APPLICATION INSTALLATION
--------------------------------------------------------------

STEP 1 — Copy the project folder
  1. Copy the folder ConstructManager into the htdocs directory:

     Windows : C:\xampp\htdocs\
     Linux   : /opt/lampp/htdocs/
     macOS   : /Applications/XAMPP/htdocs/

  Expected result:
     C:\xampp\htdocs\ConstructManager\

STEP 2 — Configure the database connection
  1. Open the file: config/database.php
  2. Verify or update the following parameters:

       $host     = 'localhost';
       $dbname   = 'construction_db';
       $username = 'root';
       $password = '';     ← leave empty by default in XAMPP

  3. Save the file

STEP 3 — Check uploads folder permissions
  1. Make sure the uploads/ folder is writable
  2. On Linux / macOS, run the following in a terminal:
       chmod 777 uploads/

STEP 4 — Launch the application
  1. Open a browser
  2. Go to the following URL:
       http://localhost/ConstructManager/
  3. The login page will appear

--------------------------------------------------------------
6. LOGIN CREDENTIALS
--------------------------------------------------------------

  Username : admin
  Password : admin123

  These credentials are defined directly in the login
  controller file. They can be changed by editing:
  controllers/AuthController.php or views/login.php

--------------------------------------------------------------
7. AI CHATBOT INSTALLATION (OPTIONAL)
--------------------------------------------------------------

The integrated chatbot runs via a local Ollama model.
The rest of the application works normally without it.

STEP 1 — Install Ollama
  1. Download Ollama from: https://ollama.com
  2. Follow the installation instructions for your OS

STEP 2 — Download a language model
  1. Open a terminal
  2. Run the following command:
       ollama pull mistral
     (or any other available model: llama2, phi, etc.)

STEP 3 — Start the Ollama server
  1. In the terminal, run:
       ollama serve
  2. The server starts at: http://localhost:11434

STEP 4 — Verify the connection
  1. The chatbot appears as an icon in the bottom-right
     corner of every page of the application
  2. Click the icon to open the chat window
  3. Ask any question related to construction management

--------------------------------------------------------------
8. APPLICATION FEATURES
--------------------------------------------------------------

  [1] Dashboard
      Global statistics, project priority badges
      (OVERDUE / HIGH / MEDIUM / LOW based on remaining days),
      recent projects panel, recent situations panel

  [2] Project Management
      List, search, add, edit, delete projects
      Detailed view with 3 tabs:
        - Works (Work Packages)
        - Situations (Progress Statements)
        - Documents (Contractual Files)

  [3] Financial Health Card
      Per project: total planned cost, earned value,
      budget consumption percentage, remaining budget,
      automatic status: ON TRACK / AT RISK / OVER BUDGET

  [4] Color-Coded Work Package Progress Bars
      Red   : completion below 30%
      Orange: completion between 30% and 75%
      Green : completion above 75%

  [5] Auto-Generated Printable Report
      "Generate Report" button on each project page
      Produces a standalone printable HTML page containing:
      project info, financial summary, work packages table,
      and situations history — with a Print button

  [6] Client and Document Management
      Full CRUD for clients, specification documents,
      work packages, situations, and uploaded documents

  [7] Integrated AI Chatbot
      Always-visible icon in the bottom-right corner
      Powered by local Ollama (no internet required)
      Fully confidential — data never leaves the machine
      Answers questions on construction site management

--------------------------------------------------------------
9. TROUBLESHOOTING
--------------------------------------------------------------

PROBLEM  : Blank page or HTTP 500 error
SOLUTION : Check that Apache and MySQL are running in XAMPP.
           Verify the settings in config/database.php.

PROBLEM  : "Access denied for user root"
SOLUTION : Check the MySQL password in database.php.
           Under XAMPP the default password is empty.

PROBLEM  : "Table not found" or database error
SOLUTION : Make sure script.sql was successfully imported
           in phpMyAdmin and that the database is named
           exactly: construction_db

PROBLEM  : Uploaded files are not saved
SOLUTION : Check write permissions on the uploads/ folder
           and make sure the folder exists.

PROBLEM  : Chatbot does not respond
SOLUTION : Verify that Ollama is running (ollama serve)
           and that a model is installed (ollama list).
           The rest of the application works without Ollama.

PROBLEM  : "Connection refused" error on the chatbot
SOLUTION : Make sure port 11434 is not blocked by a
           firewall or another running application.

PROBLEM  : CSS or layout not displaying correctly
SOLUTION : Clear your browser cache (Ctrl+Shift+R) and
           make sure the assets/ folder was copied correctly.

--------------------------------------------------------------
10. TECHNOLOGIES USED
--------------------------------------------------------------

  PHP 7.4+       MVC architecture, server-side logic
  MySQL 5.7+     Relational database management system
  Apache         Local web server (via XAMPP)
  JavaScript     Frontend interactions and filtering
  PDO            Secure PHP database connection layer
  Ollama         Local AI language model for the chatbot
  HTML5 / CSS3   Page structure and custom styling

--------------------------------------------------------------
11. CONTACT
--------------------------------------------------------------

For any questions regarding the installation or use of
this application, please contact:

  Heeba Souaf        — ENSIT, Computer Engineering, 2025-2026
  Rayhane Zarga      — ENSIT, Computer Engineering, 2025-2026
  Malek Ben Amdouni  — ENSIT, Computer Engineering, 2025-2026

  Supervisor : Mrs. Ines Bayoudh Saadi
  Institution: ENSIT — 5, Avenue Taha Hussein, Tunis
               www.ensit.tn

==============================================================
   © 2026 ENSIT — All Rights Reserved
==============================================================
