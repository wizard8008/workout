<?php
$host = 'localhost';
$dbname = 'goals';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// POST request processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exercise_names = $_POST['exercise_name'] ?? [];
    $current_states = $_POST['current'] ?? [];
    $goals = $_POST['goal'] ?? [];

    foreach ($exercise_names as $index => $exercise_name) {
        $current_state = $current_states[$index] ?? null;
        $goal = $goals[$index] ?? null;

        // Check that at least one value is filled in
        if (!empty($current_state) || !empty($goal)) {
            // Check if there is already an entry for this exercise
            $stmt = $pdo->prepare("SELECT id FROM user_goals WHERE exercise_name = :exercise_name");
            $stmt->execute(['exercise_name' => $exercise_name]);
            $existing_goal = $stmt->fetch();

            if ($existing_goal) {
                // If the record exists, update
                $update_stmt = $pdo->prepare("
                    UPDATE user_goals 
                    SET current_state = COALESCE(:current_state, current_state), 
                        goal = COALESCE(:goal, goal)
                    WHERE id = :id
                ");
                $update_stmt->execute([
                    'current_state' => $current_state,
                    'goal' => $goal,
                    'id' => $existing_goal['id'],
                ]);
            } else {
                // If there is no record, create a new one
                $insert_stmt = $pdo->prepare("
                    INSERT INTO user_goals (exercise_name, current_state, goal)
                    VALUES (:exercise_name, :current_state, :goal)
                ");
                $insert_stmt->execute([
                    'exercise_name' => $exercise_name,
                    'current_state' => $current_state,
                    'goal' => $goal,
                ]);
            }

            // Add data to progress_log
            if (!empty($current_state)) {
                $log_stmt = $pdo->prepare("
                    INSERT INTO progress_log (exercise_name, current_state, date) 
                    VALUES (:exercise_name, :current_state, CURDATE())
                    ON DUPLICATE KEY UPDATE current_state = :current_state
                ");
                $log_stmt->execute([
                    'exercise_name' => $exercise_name,
                    'current_state' => $current_state,
                ]);
            }
        }
    }
}

header("Location: set.php?success=1");
exit;
