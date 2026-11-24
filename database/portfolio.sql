
CREATE DATABASE said_khassim_portfolio;

USE said_khassim_portfolio;

-- 1. Admin accounts
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Personal profile
CREATE TABLE profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    professional_title VARCHAR(200),
    biography TEXT,
    profile_image VARCHAR(255),
    email VARCHAR(150),
    phone VARCHAR(30),
    location VARCHAR(150)
);

-- 3. Skills
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(150) NOT NULL,
    category VARCHAR(100),
    description TEXT
);

-- 4. Education
CREATE TABLE education (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qualification VARCHAR(200) NOT NULL,
    institution VARCHAR(200),
    start_date DATE,
    end_date DATE,
    status VARCHAR(50),
    description TEXT
);

-- 5. Experience
CREATE TABLE experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_title VARCHAR(200) NOT NULL,
    organisation VARCHAR(200),
    start_date DATE,
    end_date DATE,
    description TEXT
);

-- 6. Services
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(150) NOT NULL,
    description TEXT
);

-- 7. Projects
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(200) NOT NULL,
    description TEXT,
    technologies VARCHAR(255),
    project_image VARCHAR(255),
    project_status VARCHAR(100)
);

-- 8. Certificates
CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    certificate_name VARCHAR(200) NOT NULL,
    issuing_organisation VARCHAR(200),
    issue_date DATE,
    certificate_file VARCHAR(255)
);

-- 9. Visitor enquiries
CREATE TABLE inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_name VARCHAR(150) NOT NULL,
    visitor_email VARCHAR(150) NOT NULL,
    service VARCHAR(150),
    message TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. Website settings
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_name VARCHAR(150) NOT NULL UNIQUE,
    setting_value TEXT
);
