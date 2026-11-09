-- Run this ONCE in phpMyAdmin if you already have the earlier mini-project database.
USE lab_mini_project;

ALTER TABLE clients ADD COLUMN email VARCHAR(150) NULL;
ALTER TABLE clients ADD UNIQUE KEY uq_clients_email (email);

ALTER TABLE users ADD COLUMN client_id INT NULL;
ALTER TABLE users ADD COLUMN role ENUM('client','admin') NOT NULL DEFAULT 'client';
ALTER TABLE users ADD CONSTRAINT fk_users_client
    FOREIGN KEY (client_id) REFERENCES clients(client_id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- Existing users stay as client accounts. Register again with an email matching
-- a client record to link the account, or link records manually in phpMyAdmin.
