CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'manager', 'staff') DEFAULT 'staff',
  status ENUM('active', 'inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  description TEXT,
  image VARCHAR(255),
  status ENUM('active', 'inactive') DEFAULT 'active',
  order_num INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  article_code VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  sizes VARCHAR(255),
  colors VARCHAR(255),
  primary_image VARCHAR(255),
  images TEXT,
  is_featured BOOLEAN DEFAULT 0,
  status ENUM('active', 'inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS banners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255),
  image VARCHAR(255),
  button_text VARCHAR(100),
  button_link VARCHAR(255),
  status ENUM('active', 'inactive') DEFAULT 'active',
  order_num INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(50),
  company VARCHAR(255),
  message TEXT NOT NULL,
  status ENUM('new', 'read', 'replied') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) UNIQUE NOT NULL,
  value TEXT
);

-- Insert default categories (Gents, Ladies, Kids)
INSERT INTO categories (name, slug, description, status, order_num) VALUES
('Gents Collection', 'gents-collection', 'Premium footwear for men', 'active', 1),
('Ladies Collection', 'ladies-collection', 'Stylish footwear for women', 'active', 2),
('Kids Collection', 'kids-collection', 'Comfortable shoes for kids', 'active', 3),
('Sports Shoes', 'sports-shoes', 'Athletic and sports footwear', 'active', 4);

-- Insert default banner
INSERT INTO banners (title, subtitle, button_text, button_link, status, order_num) VALUES
('Welcome to Khaitan Footwear', 'Leading Manufacturer & Supplier of Quality Footwear', 'View Products', 'products.php', 'active', 1);