CREATE DATABASE quiz;
USE quiz;

-- Lietotāju tabula
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testa lietotājs (admin ar paroli "admin123")
-- Parole: admin123 (pagaidām plain text, bet labāk lietot password_hash)
-- INSERT INTO users (username, password, role) 
-- VALUES ('admin', 'admin123', 'admin')
-- ON DUPLICATE KEY UPDATE id = id;

-- Izdzēš veco admin ierakstu (ja eksistē)
DELETE FROM users WHERE username = 'admin';

-- Ievieto admin ar pareizu hashoto paroli
INSERT INTO users (username, password, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE username = 'admin';