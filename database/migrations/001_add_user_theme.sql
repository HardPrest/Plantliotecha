ALTER TABLE users
ADD COLUMN theme ENUM('light', 'dark') NOT NULL DEFAULT 'light' AFTER is_public;
