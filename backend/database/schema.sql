CREATE DATABASE IF NOT EXISTS endurotrack_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE endurotrack_db;

CREATE TABLE IF NOT EXISTS roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id INT UNSIGNED NOT NULL,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS athlete_profiles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    discipline ENUM('running', 'cycling', 'both') NOT NULL DEFAULT 'both',
    experience_level VARCHAR(30) NOT NULL DEFAULT 'beginner',
    season_goal VARCHAR(255) NULL,
    CONSTRAINT fk_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS routes (
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
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS training_sessions (
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
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS training_plans (
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
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS competitions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_by INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    discipline ENUM('running', 'cycling') NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(150) NOT NULL,
    distance_km DECIMAL(6,2) NOT NULL,
    registration_deadline DATE NOT NULL,
    CONSTRAINT fk_competitions_creator FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    competition_id INT UNSIGNED NOT NULL,
    athlete_id INT UNSIGNED NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_registration (competition_id, athlete_id),
    CONSTRAINT fk_registrations_competition FOREIGN KEY (competition_id) REFERENCES competitions(id) ON DELETE CASCADE,
    CONSTRAINT fk_registrations_athlete FOREIGN KEY (athlete_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS coach_recommendations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    coach_id INT UNSIGNED NOT NULL,
    athlete_id INT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_recommendations_coach FOREIGN KEY (coach_id) REFERENCES users(id),
    CONSTRAINT fk_recommendations_athlete FOREIGN KEY (athlete_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT IGNORE INTO roles (id, name) VALUES
    (1, 'admin'),
    (2, 'coach'),
    (3, 'athlete');

-- The sample password for all demo users is: password
INSERT IGNORE INTO users (id, role_id, first_name, last_name, email, password_hash) VALUES
    (1, 1, 'Admin', 'EnduroTrack', 'admin@endurotrack.test',
     '$2y$10$MGdNCKrx2f4un699es7sqOR5g5geZ15K2plgoeLICY6ed0hW7LewS'),
    (2, 2, 'Mila', 'Coach', 'coach@endurotrack.test',
     '$2y$10$MGdNCKrx2f4un699es7sqOR5g5geZ15K2plgoeLICY6ed0hW7LewS'),
    (3, 3, 'Dzevad', 'Hrnjic', 'athlete@endurotrack.test',
     '$2y$10$MGdNCKrx2f4un699es7sqOR5g5geZ15K2plgoeLICY6ed0hW7LewS');

INSERT IGNORE INTO athlete_profiles
    (id, user_id, discipline, experience_level, season_goal)
VALUES
    (1, 3, 'both', 'intermediate', 'Complete a spring half marathon and a cycling gran fondo');

INSERT IGNORE INTO routes
    (id, created_by, name, discipline, distance_km, elevation_gain_m, city, latitude, longitude)
VALUES
    (1, 3, 'Vilsonovo setaliste Loop', 'running', 8.40, 84, 'Sarajevo', 43.8500, 18.3900),
    (2, 3, 'Trebevic Mountain Gravel', 'cycling', 32.80, 420, 'Sarajevo', 43.8000, 18.5700);

INSERT IGNORE INTO training_sessions
    (id, athlete_id, route_id, discipline, session_date, distance_km, duration_minutes, average_speed, notes)
VALUES
    (1, 3, 1, 'running', '2026-10-21', 8.40, 44, NULL, 'Easy morning run'),
    (2, 3, 2, 'cycling', '2026-10-19', 32.80, 88, 22.40, 'Gravel endurance ride');

INSERT IGNORE INTO training_plans
    (id, coach_id, athlete_id, route_id, title, planned_date, target_distance_km, status)
VALUES
    (1, 2, 3, 1, 'Weekly aerobic base run', '2026-10-29', 10.00, 'planned');

INSERT IGNORE INTO competitions
    (id, created_by, name, discipline, event_date, location, distance_km, registration_deadline)
VALUES
    (1, 1, 'Green Hills Gran Fondo', 'cycling', '2026-11-03', 'Sarajevo, BiH', 80.00, '2026-10-30'),
    (2, 1, 'Sarajevo City Run', 'running', '2026-11-15', 'Sarajevo, BiH', 10.00, '2026-11-10');

INSERT IGNORE INTO registrations (id, competition_id, athlete_id)
VALUES (1, 1, 3);

INSERT IGNORE INTO coach_recommendations
    (id, coach_id, athlete_id, title, content)
VALUES
    (1, 2, 3, 'Keep the aerobic pace easy', 'Keep the next long run conversational and add distance gradually.');
