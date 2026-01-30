-- Add email and role columns to existing users table
ALTER TABLE users 
ADD COLUMN email VARCHAR(255) AFTER name,
ADD COLUMN role ENUM('Parent','Student') DEFAULT 'Student' AFTER education;
