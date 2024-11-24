CREATE DATABASE price_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE price_tracker;

CREATE TABLE subscriptions (
                               id INT AUTO_INCREMENT PRIMARY KEY,
                               url VARCHAR(255) NOT NULL,
                               email VARCHAR(255) NOT NULL,
                               last_price VARCHAR(50) DEFAULT NULL,
                               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               is_confirmed TINYINT(1) DEFAULT 0,
                               confirmation_token VARCHAR(255) DEFAULT NULL,
                               UNIQUE KEY unique_subscription (url, email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
