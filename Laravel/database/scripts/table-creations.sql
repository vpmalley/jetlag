-- in Homestead Vagrant VM:
-- mysql -u homestead -p homestead
-- this is to connect to DB named homestead as user homestead
-- pwd: secret

-- users, for authentication
CREATE TABLE IF NOT EXISTS users
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(200),
  email VARCHAR(200) NOT NULL,
  password VARCHAR(100) NOT NULL,
  remember_token VARCHAR(100),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
