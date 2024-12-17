<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Analytics</title>
    <style>
        body {
            background: url(/images/bg1.png) no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            font-family: 'Georgia', serif;
        }

        .header {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            flex-direction: column;  
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            border: 2px solid rgb(0, 0, 0, 1);
        }

        .header h1 {
            margin: 0;
            text-align: center;
        }

        .nav-links ul {
            list-style: none;
            display: flex;
            justify-content: center;
            margin: 20px 0;
            padding: 0;
        }

        .nav-links ul li {
            margin: 0 15px;
        }

        .nav-links ul li a {
            text-decoration: none;
            color: #b3b3b3;
            font-weight: 400;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .nav-links ul li a:hover {
            color: #ff6347;
        }

        .exercise-links ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            padding: 0;
            margin-top: 20px;
        }

        .exercise-links ul li {
            margin: 5px 0;
        }

        .exercise-links ul li a {
            text-decoration: none;
            color: #b3b3b3;
            font-size: 1.1rem;
            font-weight: 400;
            transition: color 0.3s ease;
        }

        .exercise-links ul li a:hover {
            color: #ff6347;
        }

        #charts-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        canvas {
            margin: 20px 0;
            max-width: 100%;
        }

        #reset-charts {
            margin: 20px auto;
            display: block;
            padding: 10px 20px;
            background-color: #ff6347;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease; 
        }

        #reset-charts:hover {
        background-color: #851400;
        }


        .scroll-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #ff6347;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 50px; 
        height: 50px; 
        display: flex;
        justify-content: center;
        align-items: center; 
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        display: none;
        transition: background-color 0.3s ease; 
        margin-bottom: 150px;
    }

    .scroll-to-top:hover {
        background-color: #851400;
    }

    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header class="header">
    <h1 style="color: #fff;">Progress Analytics</h1>

    <nav class="nav-links">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="set.php">Back</a></li>
        </ul>
    </nav>

    <div class="exercise-links">
        <ul>
            <li><a href="#chart-0">Abs</a></li>
            <li><a href="#chart-1">Bar Dips</a></li>
            <li><a href="#chart-2">Bench Press</a></li>
            <li><a href="#chart-3">Deadlift</a></li>
            <li><a href="#chart-4">Plank</a></li>
            <li><a href="#chart-5">Pull Ups</a></li>
            <li><a href="#chart-6">Push Ups</a></li>
            <li><a href="#chart-7">Squats</a></li>
        </ul>
    </div>
</header>


    <section>
        <div id="charts-container"></div>
        <button id="reset-charts" style="margin-bottom: 30px;">Reset All Data</button>
    </section>

    <button id="scroll-to-top" class="scroll-to-top">
    <i class="fas fa-arrow-up"></i>
    </button>

    <?php
    // Database connection
    $host = 'localhost';
    $dbname = 'goals';
    $user = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }

    // Obtaining data for all exercises
    try {
        $stmt = $pdo->prepare("SELECT exercise_name, CONCAT(date, ' ', time) AS datetime, current_state FROM progress_log ORDER BY exercise_name, datetime ASC");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Ошибка загрузки данных: " . $e->getMessage());
    }

    // Generating data for each exercise
    $exerciseData = [];
    foreach ($data as $entry) {
        $exerciseData[$entry['exercise_name']]['dates'][] = $entry['datetime'];
        $exerciseData[$entry['exercise_name']]['progress'][] = $entry['current_state'];
    }
    ?>

    <script>
        const exerciseData = <?= json_encode($exerciseData) ?>;
        const container = document.getElementById('charts-container');

        function renderCharts() {
            container.innerHTML = ''; // Cleaning the container before rendering
            Object.keys(exerciseData).forEach((exercise, index) => {
                const canvas = document.createElement('canvas');
                canvas.id = `chart-${index}`;
                canvas.width = 400;
                canvas.height = 200;
                container.appendChild(canvas);

                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: exerciseData[exercise]['dates'],
                        datasets: [{
                            label: `${exercise} Progress`,
                            data: exerciseData[exercise]['progress'],
                            borderColor: '#ff6347',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: exercise,
                                color: '#e0e0e0',
                            },
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date and Time',
                                    color: '#b3b3b3',
                                },
                                ticks: {
                                    color: '#e0e0e0',
                                },
                                grid: {
                                    color: '#444',
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Repetitions',
                                    color: '#b3b3b3',
                                },
                                ticks: {
                                    color: '#b3b3b3',
                                },
                                grid: {
                                    color: '#444',
                                }
                            }
                        }
                    }
                });
            });
        }

        // Graph drawing
        renderCharts();

        // Event handler for resetting charts
        document.getElementById('reset-charts').addEventListener('click', renderCharts);

        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('nav a[href^="#"]');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

                    // Function for resetting graphs and data
            document.getElementById('reset-charts').addEventListener('click', () => {
                if (confirm('Are you sure you want to reset all the data?')) {
                    fetch('reset_progress.php', { method: 'POST' })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert('Data reset!');
                                Object.keys(exerciseData).forEach(exercise => {
                                    exerciseData[exercise].dates = [];
                                    exerciseData[exercise].progress = [];
                                });
                                renderCharts(); // Redrawing empty charts
                            } else {
                                alert('Data reset error!');
                            }
                        })
                        .catch(() => alert('A query error occurred.'));
                }
            });

            renderCharts();

            // Появление кнопки при прокрутке
            window.addEventListener('scroll', () => {
                const button = document.getElementById('scroll-to-top');
                if (window.scrollY > 200) {
                    button.style.display = 'block'; // Show button
                } else {
                    button.style.display = 'none'; // Hide button
                }
            });

            // Scroll to the top of the page
            document.getElementById('scroll-to-top').addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
