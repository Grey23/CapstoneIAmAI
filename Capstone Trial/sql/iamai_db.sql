CREATE DATABASE IF NOT EXISTS iamai_db;
USE iamai_db;

-- Create users table first
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

-- Create indexes for users table
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_role ON users(role);

-- Then create projects table
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  `project_description` text DEFAULT NULL,
  `project_status` enum('Pending','In Progress','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `client_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `visible_to_client_only` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `assigned_user_id` (`assigned_user_id`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE users
ADD COLUMN two_factor_enabled TINYINT(1) DEFAULT 0,
ADD COLUMN notification_preferences TINYINT(1) DEFAULT 1,
ADD COLUMN theme_preference VARCHAR(10) DEFAULT 'light',
ADD COLUMN last_login DATETIME,
ADD COLUMN last_login_ip VARCHAR(45);


-- Create notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create indexes for notifications table
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(is_read);