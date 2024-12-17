CREATE TABLE user_goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_name VARCHAR(255) NOT NULL,
    current INT DEFAULT NULL,
    goal INT DEFAULT NULL
);

CREATE TABLE progress_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exercise_name VARCHAR(50) NOT NULL,
    current_state INT NOT NULL,
    date DATE NOT NULL
);

CREATE TABLE workout_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    total_time VARCHAR(10) NOT NULL,
    completed INT NOT NULL,
    not_completed INT NOT NULL,
    details TEXT NOT NULL
);
