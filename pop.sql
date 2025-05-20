-- Create the database
CREATE DATABASE IF NOT EXISTS truesecure_insurance;
USE truesecure_insurance;

-- Create the users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    user_firstName VARCHAR(100) NOT NULL,
    user_lastName VARCHAR(100) NOT NULL
);

-- Create the premiums table
CREATE TABLE premiums (
    premium_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    premium_amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) NOT NULL,
    premium_expiry DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Create the claims table
CREATE TABLE claims (
    claim_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    claim_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insert sample data into users table
INSERT INTO users (user_email, user_password, user_firstName, user_lastName) VALUES
('john.doe@example.com', 'password123', 'John', 'Doe'),
('jane.smith@example.com', 'pass456', 'Jane', 'Smith'),
('bob.jones@example.com', 'bob789', 'Bob', 'Jones');

-- Insert sample data into premiums table
INSERT INTO premiums (user_id, premium_amount, currency, premium_expiry) VALUES
(1, 500.00, 'USD', '2025-12-31 23:59:59'), -- John Doe, active premium
(2, 420.00, 'EUR', '2024-06-01 23:59:59'), -- Jane Smith, expired premium
(3, 40000.00, 'INR', '2025-03-31 23:59:59'); -- Bob Jones, active premium

-- Insert sample data into claims table
INSERT INTO claims (user_id, claim_type, description, amount, status) VALUES
(1, 'Medical', 'Hospital stay due to flu', 1500.00, 'Pending'),
(1, 'Accident', 'Car accident repair', 2000.00, 'Approved'),
(2, 'Death', 'Life insurance claim for family member', 10000.00, 'Rejected'),
(3, 'Medical', 'Dental surgery', 12000.00, 'Pending');