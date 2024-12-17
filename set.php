<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Goals</title>
    <link rel="stylesheet" href="css/style_main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

        <?php
        $host = 'localhost';
        $dbname = 'goals';
        $user = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }

        try {
            $stmt = $pdo->query("SELECT exercise_name, current_state, goal FROM user_goals");
            $saved_goals = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Data loading error: " . $e->getMessage());
        }

        $goals_map = [];
        foreach ($saved_goals as $goal) {
            $goals_map[$goal['exercise_name']] = $goal;
        }
        ?>

<style>
    body {
        background: url(/images/bg1.png) no-repeat center center fixed;
        background-size: cover;
    }
</style>
<body>
    
    <header class="header">
        <h1>Set Your Points</h1>
        <p>In the title of every ex. u can find a instruction!</p><br>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="analytics.php">Analytics</a></li>
            </ul>
        </nav>
    </header>
        
        <div class="message-container">
            <?php
        if (!empty($_GET['success'])) {
            echo "<div class='success-message'>Data successfully updated!</div>";
        }
        ?>
    </div>
    
    <form action="save_goals.php" method="POST">
        <section class="list">
            
            <div class="case <?php if (($goals_map['Pull Ups']['current_state'] ?? 0) >= ($goals_map['Pull Ups']['goal'] ?? PHP_INT_MAX)) echo 'achieved'; ?>">
                <img src="images/pull_ups.png" alt="pull ups image">
                <h3><a href="https://thegravgear.com/blogs/calisthenics/perfect-pull-up" target="_blank" class="exercise-link">Pull Ups</a></h3>
                <input type="hidden" name="exercise_name[]" value="Pull Ups">
                <div class="goal-inputs">
                    <input type="number" placeholder="Current State"
                    name="current[]" value="<?= $goals_map['Pull Ups']['current_state'] ?? '' ?>">
                    <input type="number" placeholder="Goal"
                        name="goal[]" value="<?= $goals_map['Pull Ups']['goal'] ?? '' ?>">
                </div>
            </div>

            <div class="case <?php if (($goals_map['Push Ups']['current_state'] ?? 0) >= ($goals_map['Push Ups']['goal'] ?? PHP_INT_MAX)) echo 'achieved'; ?>">
                <img src="images/push_ups.png" alt="push ups image">
                <h3>Push Ups</h3>
                <input type="hidden" name="exercise_name[]" value="Push Ups">
                <div class="goal-inputs">
                    <input type="number" placeholder="Current State"
                        name="current[]" value="<?= $goals_map['Push Ups']['current_state'] ?? '' ?>">
                    <input type="number" placeholder="Goal"
                        name="goal[]" value="<?= $goals_map['Push Ups']['goal'] ?? '' ?>">
                </div>
            </div>

            <div class="case <?php if (($goals_map['Abs']['current_state'] ?? 0) >= ($goals_map['Abs']['goal'] ?? PHP_INT_MAX)) echo 'achieved'; ?>">
                <img src="images/abs.png" alt="abs image">
                <h3>Abs</h3>
                <input type="hidden" name="exercise_name[]" value="Abs">
                <div class="goal-inputs">
                    <input type="number" placeholder="Current State"
                        name="current[]" value="<?= $goals_map['Abs']['current_state'] ?? '' ?>">
                    <input type="number" placeholder="Goal"
                        name="goal[]" value="<?= $goals_map['Abs']['goal'] ?? '' ?>">
                </div>
            </div>

            <div class="case <?php if (($goals_map['Bar Dips']['current_state'] ?? 0) >= ($goals_map['Bar Dips']['goal'] ?? PHP_INT_MAX)) echo 'achieved'; ?>">
                <img src="images/dips.png" alt="dips image">
                <h3>Bar Dips</h3>
                <input type="hidden" name="exercise_name[]" value="Bar Dips">
                <div class="goal-inputs">
                    <input type="number" placeholder="Current State"
                        name="current[]" value="<?= $goals_map['Bar Dips']['current_state'] ?? '' ?>">
                    <input type="number" placeholder="Goal"
                        name="goal[]" value="<?= $goals_map['Bar Dips']['goal'] ?? '' ?>">
                </div>
            </div>

            <div class="case <?php if (($goals_map['Squats']['current_state'] ?? 0) >= ($goals_map['Squats']['goal'] ?? PHP_INT_MAX)) echo 'achieved'; ?>">
                <img src="images/squats.png" alt="squats image">
                <h3>Squats</h3>
                <input type="hidden" name="exercise_name[]" value="Squats">
                <div class="goal-inputs">
                    <input type="number" placeholder="Current State"
                        name="current[]" value="<?= $goals_map['Squats']['current_state'] ?? '' ?>">
                    <input type="number" placeholder="Goal"
                        name="goal[]" value="<?= $goals_map['Squats']['goal'] ?? '' ?>">
                </div>
            </div>

            <div class="case <?php if (($goals_map['Bench Press']['current_state'] ?? 0) >= ($goals_map['Bench Press']['goal'] ?? PHP_INT_MAX)) echo 'achieved'; ?>">
                <img src="images/banch.png" alt="bench press image">
                <h3>Bench Press</h3>
                <input type="hidden" name="exercise_name[]" value="Bench Press">
                <div class="goal-inputs">
                    <input type="number" placeholder="Current State"
                        name="current[]" value="<?= $goals_map['Bench Press']['current_state'] ?? '' ?>">
                    <input type="number" placeholder="Goal"
                        name="goal[]" value="<?= $goals_map['Bench Press']['goal'] ?? '' ?>">
                </div>
            </div>

            <div class="case <?php if (($goals_map['Plank']['current_state'] ?? 0) >= ($goals_map['Plank']['goal'] ?? PHP_INT_MAX)) echo 'achieved'; ?>">
                <img src="images/plank.png" alt="plank image">
                <h3>Plank</h3>
                <input type="hidden" name="exercise_name[]" value="Plank">
                <div class="goal-inputs">
                    <input type="number" placeholder="Current State"
                        name="current[]" value="<?= $goals_map['Plank']['current_state'] ?? '' ?>">
                    <input type="number" placeholder="Goal"
                        name="goal[]" value="<?= $goals_map['Plank']['goal'] ?? '' ?>">
                </div>
            </div>

            <div class="case <?php if (($goals_map['Deadlift']['current_state'] ?? 0) >= ($goals_map['Deadlift']['goal'] ?? PHP_INT_MAX)) echo 'achieved'; ?>">
                <img src="images/deadlift.png" alt="deadlift image">
                <h3>Deadlift</h3>
                <input type="hidden" name="exercise_name[]" value="Deadlift">
                <div class="goal-inputs">
                    <input type="number" placeholder="Current State"
                        name="current[]" value="<?= $goals_map['Deadlift']['current_state'] ?? '' ?>">
                    <input type="number" placeholder="Goal"
                        name="goal[]" value="<?= $goals_map['Deadlift']['goal'] ?? '' ?>">
                </div>
            </div>
        </section>
        
        <div align="center" style="margin: 10px;">
            <button type="submit" class="btn">Update</button>
        </div>
    </form>
    
</body>

<?php include 'footer.php'; ?>

</html>
