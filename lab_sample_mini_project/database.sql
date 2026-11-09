CREATE DATABASE IF NOT EXISTS lab_mini_project;
USE lab_mini_project;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS login_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS clients (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(30),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS samples (
    sample_id INT AUTO_INCREMENT PRIMARY KEY,
    sample_code VARCHAR(50) NOT NULL UNIQUE,
    client_id INT NOT NULL,
    sample_name VARCHAR(100) NOT NULL,
    collection_date DATE NOT NULL,
    source_location VARCHAR(150),
    FOREIGN KEY (client_id) REFERENCES clients(client_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS test_results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    sample_id INT NOT NULL,
    test_name VARCHAR(100) NOT NULL,
    result_value VARCHAR(100) NOT NULL,
    test_date DATE NOT NULL,
    FOREIGN KEY (sample_id) REFERENCES samples(sample_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);
