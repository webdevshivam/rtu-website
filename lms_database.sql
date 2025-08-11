
-- LMS Database for RTU Students
-- Database: lms_rtu

CREATE DATABASE IF NOT EXISTS lms_rtu;
USE lms_rtu;

-- Users table
CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'content_manager', 'student') NOT NULL DEFAULT 'student',
    avatar VARCHAR(255) NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Semesters table
CREATE TABLE semesters (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) NOT NULL UNIQUE,
    description TEXT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects table
CREATE TABLE subjects (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    semester_id INT(11) UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL,
    description TEXT NULL,
    color VARCHAR(7) DEFAULT '#3B82F6',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE CASCADE
);

-- Topics table
CREATE TABLE topics (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subject_id INT(11) UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    order_index INT(11) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Subtopics table
CREATE TABLE subtopics (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    topic_id INT(11) UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    order_index INT(11) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
);

-- Content table
CREATE TABLE content (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subtopic_id INT(11) UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    content_type ENUM('video', 'note', 'pdf', 'link') NOT NULL,
    file_url VARCHAR(500) NULL,
    youtube_url VARCHAR(500) NULL,
    file_size INT(11) NULL,
    duration VARCHAR(20) NULL,
    thumbnail VARCHAR(500) NULL,
    download_count INT(11) DEFAULT 0,
    view_count INT(11) DEFAULT 0,
    created_by INT(11) UNSIGNED NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subtopic_id) REFERENCES subtopics(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Bookmarks table
CREATE TABLE bookmarks (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) UNSIGNED NOT NULL,
    content_id INT(11) UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE,
    UNIQUE KEY unique_bookmark (student_id, content_id)
);

-- Activity log table
CREATE TABLE activity_log (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    action VARCHAR(100) NOT NULL,
    content_id INT(11) UNSIGNED NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE SET NULL
);

-- User progress table
CREATE TABLE user_progress (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    content_id INT(11) UNSIGNED NOT NULL,
    progress_percentage DECIMAL(5,2) DEFAULT 0.00,
    last_position VARCHAR(20) NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES content(id) ON DELETE CASCADE,
    UNIQUE KEY unique_progress (user_id, content_id)
);

-- Last visited table
CREATE TABLE last_visited (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NOT NULL,
    subtopic_id INT(11) UNSIGNED NOT NULL,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subtopic_id) REFERENCES subtopics(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user (user_id)
);

-- Sample data for testing
INSERT INTO semesters (name, code, description) VALUES 
('First Semester', 'SEM1', 'First semester courses'),
('Second Semester', 'SEM2', 'Second semester courses'),
('Third Semester', 'SEM3', 'Third semester courses'),
('Fourth Semester', 'SEM4', 'Fourth semester courses');

INSERT INTO subjects (semester_id, name, code, description, color) VALUES
(1, 'Engineering Mathematics-I', 'MATH101', 'Basic mathematics for engineering', '#EF4444'),
(1, 'Engineering Physics', 'PHY101', 'Physics fundamentals', '#3B82F6'),
(1, 'Programming in C', 'CS101', 'Introduction to C programming', '#10B981'),
(2, 'Engineering Mathematics-II', 'MATH201', 'Advanced mathematics', '#EF4444'),
(2, 'Data Structures', 'CS201', 'Data structures and algorithms', '#8B5CF6');

INSERT INTO topics (subject_id, name, description, order_index) VALUES
(1, 'Differential Calculus', 'Basic differential calculus concepts', 1),
(1, 'Integral Calculus', 'Integration methods and applications', 2),
(3, 'Introduction to C', 'C programming basics', 1),
(3, 'Control Structures', 'Loops and conditional statements', 2),
(3, 'Functions and Arrays', 'Functions and array handling', 3);

INSERT INTO subtopics (topic_id, name, description, order_index) VALUES
(1, 'Limits and Continuity', 'Understanding limits and continuity', 1),
(1, 'Derivatives', 'Differentiation rules and applications', 2),
(3, 'History of C', 'Background and evolution of C language', 1),
(3, 'Basic Syntax', 'C syntax and structure', 2),
(3, 'Variables and Data Types', 'Understanding C data types', 3);

-- Create a test student user
INSERT INTO users (name, email, password_hash, role) VALUES
('Test Student', 'student@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Admin User', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
