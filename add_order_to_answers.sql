-- Add order_no column to existing answers table
ALTER TABLE answers 
ADD COLUMN order_no INT DEFAULT 0;
