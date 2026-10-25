CREATE DATABASE IF NOT EXISTS endurotrack_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE endurotrack_db;

CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id INT UNSIGNED NOT NULL,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE athlete_profiles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    discipline ENUM('running', 'cycling', 'both') NOT NULL DEFAULT 'both',
    experience_level VARCHAR(30) NOT NULL DEFAULT 'beginner',
    season_goal VARCHAR(255) NULL,
    CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE routes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_by INT UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL,
    discipline ENUM('running', 'cycling') NOT NULL,
    distance_km DECIMAL(6,2) NOT NULL,
    elevation_gain_m INT UNSIGNED DEFAULT 0,
    city VARCHAR(100) NOT NULL,
    latitude DECIMAL(10,7) NULL,
    longitude DECIMAL(10,7) NULL,
    CONSTRAINT fk_routes_creator FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE training_sessions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    athlete_id INT UNSIGNED NOT NULL,
    route_id INT UNSIGNED NULL,
    discipline ENUM('running', 'cycling') NOT NULL,
    session_date DATE NOT NULL,
    distance_km DECIMAL(6,2) NOT NULL,
    duration_minutes INT UNSIGNED NOT NULL,
    average_speed DECIMAL(6,2) NULL,
    notes TEXT NULL,
    CONSTRAINT fk_sessions_athlete FOREIGN KEY (athlete_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_sessions_route FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE SET NULL
);

CREATE TABLE training_plans (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    coach_id INT UNSIGNED NOT NULL,
    athlete_id INT UNSIGNED NOT NULL,
    route_id INT UNSIGNED NULL,
    title VARCHAR(150) NOT NULL,
    planned_date DATE NOT NULL,
    target_distance_km DECIMAL(6,2) NULL,
    status ENUM('planned', 'completed', 'skipped') DEFAULT 'planned',
    CONSTRAINT fk_plans_coach FOREIGN KEY (coach_id) REFERENCES users(id),
    CONSTRAINT fk_plans_athlete FOREIGN KEY (athlete_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_plans_route FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE SET NULL
);

CREATE TABLE competitions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_by INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    discipline ENUM('running', 'cycling') NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(150) NOT NULL,
    distance_km DECIMAL(6,2) NOT NULL,
    registration_deadline DATE NOT NULL,
    CONSTRAINT fk_competitions_creator FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    competition_id INT UNSIGNED NOT NULL,
    athlete_id INT UNSIGNED NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_registration (competition_id, athlete_id),
    CONSTRAINT fk_registrations_competition FOREIGN KEY (competition_id) REFERENCES competitions(id) ON DELETE CASCADE,
    CONSTRAINT fk_registrations_athlete FOREIGN KEY (athlete_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE coach_recommendations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    coach_id INT UNSIGNED NOT NULL,
    athlete_id INT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_recommendations_coach FOREIGN KEY (coach_id) REFERENCES users(id),
    CONSTRAINT fk_recommendations_athlete FOREIGN KEY (athlete_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO roles (name) VALUES ('admin'), ('coach'), ('athlete');

