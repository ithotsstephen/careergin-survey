-- Add target_role column to existing questions table
ALTER TABLE questions 
ADD COLUMN target_role ENUM('Parent','Student','Both') DEFAULT 'Both' AFTER answer_type;
