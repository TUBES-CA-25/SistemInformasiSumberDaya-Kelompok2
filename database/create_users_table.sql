-- Tabel Users untuk Admin
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'admin',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Admin User (Password: admin123)
-- Password hashed with PHP password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$9dZeOKeyCyGLbICQl4l2S.rhW9VQd7Tj5iqbdSe43yG1YKUv3Utey', 'admin');

