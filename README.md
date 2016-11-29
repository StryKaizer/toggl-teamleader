# Toggl to Teamleader

## About
Simple php script which imports toggl time entries for a specific project into teamleader.

## Installation

Copy default.config.php to config.php and alter variables

Create a database and run sql below

    CREATE TABLE `mapping` (
    `toggl_id` int(11) NOT NULL,
    `toggl_project_id` varchar(45) DEFAULT NULL,
    `description` varchar(45) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    ALTER TABLE `mapping`
    ADD PRIMARY KEY (`toggl_id`);
  
-    
Created by StryKaizer (Jimmy Henderickx)