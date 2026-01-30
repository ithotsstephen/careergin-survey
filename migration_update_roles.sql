-- Migration script to update role enums for School Student and College Student separation
-- Run this on your existing database

-- Step 1: Update the users table to add School Student and College Student roles
ALTER TABLE `users` 
MODIFY COLUMN `role` enum('Parent','School Student','College Student','Student') DEFAULT 'Student';

-- Step 2: Update the questions table to add School Student and College Student target roles
ALTER TABLE `questions` 
MODIFY COLUMN `target_role` enum('Parent','School Student','College Student','Both') DEFAULT 'Both';

-- Step 3: Update existing questions to use the new role values
-- Questions 38-64 should be for School Students
UPDATE `questions` 
SET `target_role` = 'School Student', `order_no` = id - 37
WHERE `id` BETWEEN 38 AND 64 AND `survey_id` = 1;

-- Questions 65-86 should be for College Students
UPDATE `questions` 
SET `target_role` = 'College Student', `order_no` = id - 39
WHERE `id` BETWEEN 65 AND 86 AND `survey_id` = 1;

-- Questions 87-112 should remain as Parent
UPDATE `questions` 
SET `target_role` = 'Parent', `order_no` = id - 39
WHERE `id` >= 87 AND `survey_id` = 1;

-- Step 4: Update existing users who have 'Student' role to a default
-- You may want to manually update these based on their age/education
-- For now, this is commented out. Uncomment and adjust as needed:
-- UPDATE `users` SET `role` = 'School Student' WHERE `role` = 'Student' AND `age` < 18;
-- UPDATE `users` SET `role` = 'College Student' WHERE `role` = 'Student' AND `age` >= 18;

SELECT 'Migration completed successfully!' as status;
