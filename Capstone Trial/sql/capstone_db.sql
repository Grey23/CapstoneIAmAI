-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS capstone_db;
USE capstone_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    remember_token VARCHAR(255) DEFAULT NULL,
    token_expires DATETIME DEFAULT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    company VARCHAR(100) DEFAULT NULL
);

-- Create indexes for faster queries
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_role ON users(role);

INSERT INTO users (email, password, full_name, role, phone, company) 
VALUES (
    'client@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Client User', 
    'CLIENT',
    '123-456-7890',
    'Example Company'
);

-- Insert an admin user
INSERT INTO users (email, password, full_name, role) 
VALUES (
    'admin@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Admin User', 
    'ADMIN'
);

-- Insert a CSR user
INSERT INTO users (email, password, full_name, role) 
VALUES (
    'csr@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'CSR User', 
    'CSR'
);

-- Insert a DESIGNING user
INSERT INTO users (email, password, full_name, role) 
VALUES (
    'designer@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Designer User', 
    'DESIGNING'
);

-- Insert a PRINTING user
INSERT INTO users (email, password, full_name, role) 
VALUES (
    'printer@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Printer User', 
    'PRINTING'
);

-- Create directory for profile pictures if it doesn't exist
-- Note: This is a comment for you to manually create the directory
-- mkdir -p c:\xampp\htdocs\Capstone Trial\uploads\profile_pictures


-- Create notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create index for faster notification queries
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(is_read);