    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Workout</title>
        <link rel="stylesheet" href="css/style_main.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <style>
    body {
        background: url(/images/bg1.png) no-repeat center center fixed;
        background-size: cover;
    }
    </style>

    <body>
        <header class="header">
            <h1>Workout</h1>
            <p>In the title of every ex. u can find a instruction!</p>
            <br> 
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                </ul>
            </nav>
        </header>
        <main>
                <!-- Секция упражнений пользователя -->
                <h2 class="section-title" style="margin-top: 30px;">Current Train List</h2>

            <div id="workout-list" class="list"></div>


            <div style="display: flex; justify-content: center; gap: 10px; margin-top: 0px;">
                <button id="startWorkoutButton" class="btn" onclick="showFinishButton()" style="width: 14%;">Start Train</button>
                <button id="finishWorkoutButton" class="btn" style="display: none; width: 14%">Finish Train</button>
            </div>


            <div class="container">
                <div id="timerContainer" style="display: none;">
                    <h2>Rest Timer</h2>
                    <input type="number" id="timerInput" placeholder="Time (decimal)">
                    <button id="startTimerButton" class="btn">Start</button> <!-- Кнопка Start с классом btn -->
                    <button id="resetTimerButton" class="btn">Reset</button> <!-- Кнопка Reset с классом btn -->
                    <p id="timerDisplay"></p>
                </div>

                <div id="totalTimeContainer" style="display: none;">
                    <h2>Total Time</h2>
                    <p id="totalTimeDisplay">00:00</p>
                </div>
            </div>  
            <!-- Секция всех упражнений -->
                <div style="margin-top: 25px;">
                    <h2 class="section-title">All Exercises</h2>
                <div id="exercise-list" class="list">
                    <div class="exercise-card" data-exercise="Pull Ups">
                        <img src="images/pull_ups.png" alt="pull ups image">
                        <h3><a href="https://thegravgear.com/blogs/calisthenics/perfect-pull-up" target="_blank" class="exercise-link">Pull Ups</a></h3>
                    </div>
                    <div class="exercise-card" data-exercise="Push Ups">
                        <img src="images/push_ups.png" alt="push ups image">
                        <h3>Push Ups</h3>
                    </div>
                    <div class="exercise-card" data-exercise="Abs">
                        <img src="images/abs.png" alt="abs image">
                        <h3>Abs</h3>
                    </div>
                    <div class="exercise-card" data-exercise="Bar Dips">
                        <img src="images/dips.png" alt="bar dips image">
                        <h3>Bar Dips</h3>
                    </div>
                    <div class="exercise-card" data-exercise="Squats">
                        <img src="images/squats.png" alt="squats image">
                        <h3>Squats</h3>
                    </div>
                    <div class="exercise-card" data-exercise="Bench Press">
                        <img src="images/banch.png" alt="bench press image">
                        <h3>Bench Press</h3>
                    </div>
                    <div class="exercise-card" data-exercise="Plank">
                        <img src="images/plank.png" alt="plank image">
                        <h3>Plank</h3>
                    </div>
                    <div class="exercise-card" data-exercise="Deadlift">
                        <img src="images/deadlift.png" alt="deadlift image">
                        <h3>Deadlift</h3>
                    </div>
                </div>
        </main>

        <div id="workout-history"></div>
        <div style="text-align: center; margin-top: 20px;">
            <button id="clearWorkoutHistoryButton" class="btn" style="width: 14%;">Clear History</button>
        </div>


        <?php include 'footer.php'; ?>

    <script>
                // Обработчик кнопки Start Workout
                let totalTimeStart = null;
                let totalTimeInterval = null;
                let isWorkoutStarted = false; // Флаг для отслеживания начала тренировки

                // Функция обновления таймера
                function updateTotalTime() {
                    if (totalTimeStart) {
                        const elapsedTime = Math.floor((new Date().getTime() - totalTimeStart) / 1000);
                        const minutes = String(Math.floor(elapsedTime / 60)).padStart(2, '0');
                        const seconds = String(elapsedTime % 60).padStart(2, '0');
                        document.getElementById('totalTimeDisplay').textContent = `${minutes}:${seconds}`;
                    }
                }

                // Обработчик кнопки Start Workout
                document.getElementById('startWorkoutButton').addEventListener('click', function () {
                // Если тренировка уже началась
                if (isWorkoutStarted) {
                    const confirmRestart = confirm('A workout is already in progress. Do you want to restart? This will reset the current progress.');
                    if (!confirmRestart) {
                        return; // Выходим, если пользователь не подтвердил перезапуск
                    }

                    // Очищаем предыдущие данные тренировки
                    clearInterval(totalTimeInterval);
                    totalTimeStart = null;
                    document.getElementById('totalTimeDisplay').textContent = '00:00';
                }
                    const workoutCards = document.querySelectorAll('#workout-list .exercise-card');
                    let isValid = true; // Считаем, что данные корректны
                    let hasSelectedCards = false; // Флаг для проверки наличия выбранных карточек

                    // Проверка всех карточек
                    workoutCards.forEach(card => {
                        const setsInput = card.querySelector('.sets-input');
                        const repsInput = card.querySelector('.reps-input');

                        if (setsInput && repsInput) {
                            hasSelectedCards = true; // Найдена хотя бы одна выбранная карточка
                            if (setsInput.value.trim() === '' || repsInput.value.trim() === '' || setsInput.value <= 0 || repsInput.value <= 0) {
                                isValid = false; // Проверка на незаполненные или некорректные поля
                            }
                        }
                    });

                    // Проверка на наличие выбранных карточек
                    if (!hasSelectedCards) {
                        alert('Please select at least one exercise and fill in both Sets and Reps!');
                        return;
                    }

                    // Проверка на корректность данных
                    if (!isValid) {
                        alert('Please fill in both Sets and Reps for ALL selected exercises with values greater than 0!');
                        return;
                    }


                    // Устанавливаем флаг, что тренировка началась
                    isWorkoutStarted = true;

                    // Показать таймер
                    document.getElementById('totalTimeContainer').style.display = 'block';
                    document.getElementById('timerContainer').style.display = 'block';

                    totalTimeStart = new Date().getTime();
                    totalTimeInterval = setInterval(updateTotalTime, 1000);

                    // Блокировка редактирования карточек
                    workoutCards.forEach(card => {
                        const setsInput = card.querySelector('.sets-input');
                        const repsInput = card.querySelector('.reps-input');

                        if (!card.querySelector('.done-checkbox')) {
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.classList.add('done-checkbox');
                            checkbox.title = 'Mark as completed';
                            checkbox.style.transform = 'scale(1.5)';
                            card.appendChild(checkbox);
                        }

                        // Поля Sets и Reps становятся нередактируемыми
                        if (setsInput) setsInput.setAttribute('readonly', true);
                        if (repsInput) repsInput.setAttribute('readonly', true);

                        // Отключаем перемещение карточек
                        card.style.pointerEvents = 'none';

                        // Разрешаем только взаимодействие с чекбоксом
                        const checkbox = card.querySelector('.done-checkbox');
                        if (checkbox) checkbox.style.pointerEvents = 'auto';
                    });

                    // Показать кнопку завершения тренировки
                    document.getElementById('finishWorkoutButton').style.display = 'block';
                });

      // Функция для загрузки истории из localStorage
    function loadWorkoutHistory() {
        const historyContainer = document.getElementById('workout-history');
        const savedHistory = JSON.parse(localStorage.getItem('workoutHistory')) || [];

        historyContainer.innerHTML = '<h2 class="section-title">Workout History</h2>';
        savedHistory.forEach((workout, index) => {
            const workoutDetails = `
                <details>
                    <summary>Workout #${index + 1} - ${new Date(workout.date).toLocaleString()}</summary>
                    <p><strong>Total Time:</strong> ${workout.totalTime}</p>
                    <p><strong>Completed Exercises:</strong> ${workout.completed}</p>
                    <p><strong>Not Completed Exercises:</strong> ${workout.notCompleted}</p>
                    <p><strong>Details:</strong><br>${workout.details.join('<br>')}</p>
                </details>
            `;
            historyContainer.innerHTML += workoutDetails;
        });
    }

        // Обработчик кнопки Finish Workout с сохранением в localStorage
        document.getElementById('finishWorkoutButton').addEventListener('click', function () {
            if (!totalTimeStart) return;

            clearInterval(totalTimeInterval);

            const workoutCards = document.querySelectorAll('#workout-list .exercise-card');
            let completed = 0;
            let notCompleted = 0;
            const details = [];

            workoutCards.forEach(card => {
                const checkbox = card.querySelector('.done-checkbox');
                const exerciseName = card.dataset.exercise || 'Unnamed Exercise';
                const setsInput = card.querySelector('.sets-input');
                const repsInput = card.querySelector('.reps-input');
                const sets = setsInput ? setsInput.value : 'N/A';
                const reps = repsInput ? repsInput.value : 'N/A';

                if (checkbox && checkbox.checked) {
                    completed++;
                    details.push(`${exerciseName} > Sets: ${sets}, Reps: ${reps}`);
                } else {
                    notCompleted++;
                }
            });

            const totalTime = document.getElementById('totalTimeDisplay').textContent;

            // Формируем данные тренировки
            const workoutData = {
                date: new Date().toISOString(),
                totalTime: totalTime,
                completed: completed,
                notCompleted: notCompleted,
                details: details
            };

            // Сохраняем в localStorage
            const savedHistory = JSON.parse(localStorage.getItem('workoutHistory')) || [];
            savedHistory.push(workoutData);
            localStorage.setItem('workoutHistory', JSON.stringify(savedHistory));

            // Обновляем историю на странице
            loadWorkoutHistory();

            // Перезагрузка страницы
            setTimeout(() => {
                location.reload();
            }, 0);
        });

        

    // Загружаем историю при загрузке страницы
    document.addEventListener('DOMContentLoaded', loadWorkoutHistory);  


    document.getElementById('clearWorkoutHistoryButton').addEventListener('click', function () {
    if (confirm('Are you sure you want to clear all saved workouts? This action cannot be undone.')) {
        localStorage.removeItem('workoutHistory'); // Удаляем сохраненные тренировки
        loadWorkoutHistory(); // Обновляем отображение истории
    }
    });

                // Перемещение карточек между списками
                document.addEventListener('DOMContentLoaded', () => {
            const exerciseList = document.getElementById('exercise-list');
            const workoutList = document.getElementById('workout-list');
            const originalCards = [...exerciseList.children];

            document.body.addEventListener('click', (e) => {
                const card = e.target.closest('.exercise-card');

                if (!card) return;

                // Проверка, был ли клик по полям ввода
                const setsInput = card.querySelector('.sets-input');
                const repsInput = card.querySelector('.reps-input');

                // Если клик был по полю ввода, то игнорируем обработку возвращения карточки
                if (setsInput === e.target || repsInput === e.target) {
                    return; // Не возвращаем карточку назад, если клик по полю ввода
                }
                
                // Блокируем возврат карточек, если тренировка началась
                if (isWorkoutStarted && workoutList.contains(card)) {
                    return; // Карточки не могут вернуться назад, если тренировка началась
                }

                // Обработка перемещения карточки в список упражнений
                if (exerciseList.contains(card)) {
                    const setsInput = document.createElement('input');
                    setsInput.type = 'number';
                    setsInput.placeholder = 'Sets';
                    setsInput.classList.add('sets-input');

                    const repsInput = document.createElement('input');
                    repsInput.type = 'number';
                    repsInput.placeholder = 'Reps';
                    repsInput.classList.add('reps-input');

                    card.appendChild(setsInput);
                    card.appendChild(repsInput);
                    workoutList.appendChild(card);
                } else if (workoutList.contains(card)) {
                    if (confirm('Are you sure? The data will not be saved!')) {
                        const setsInput = card.querySelector('.sets-input');
                        const repsInput = card.querySelector('.reps-input');
                        if (setsInput) setsInput.remove();
                        if (repsInput) repsInput.remove();
                        const originalIndex = originalCards.indexOf(card);
                        exerciseList.insertBefore(card, originalCards[originalIndex + 1] || null);
                    }
                }
            });
        });
        let restTimerInterval = null; // Переменная для хранения интервала
        let remainingTime = 0; // Время, оставшееся до окончания таймера

        // Обработчик кнопки Start Timer
        document.getElementById('startTimerButton').addEventListener('click', function () {
    const timerInput = document.getElementById('timerInput');
    const timerDisplay = document.getElementById('timerDisplay');

    // Проверяем, ввел ли пользователь время
    let inputValue = parseFloat(timerInput.value); // Считываем дробное значение
    if (!inputValue || inputValue <= 0) {
        alert('Please enter a valid rest time (greater than 0).');
        return;
    }

    // Переводим время в секунды и округляем вверх
    remainingTime = Math.ceil(inputValue * 60);

    // Сбрасываем предыдущий таймер, если он существует
    if (restTimerInterval) {
        clearInterval(restTimerInterval);
    }

    // Запускаем новый таймер
    restTimerInterval = setInterval(() => {
        if (remainingTime > 0) {
            remainingTime--;
            const minutes = Math.floor(remainingTime / 60).toString().padStart(2, '0');
            const seconds = (remainingTime % 60).toString().padStart(2, '0');
            timerDisplay.textContent = `${minutes}:${seconds}`;
        } else {
            clearInterval(restTimerInterval);
            restTimerInterval = null;
            timerDisplay.textContent = 'Time is Over!';
            timerInput.removeAttribute('disabled'); // Разблокируем ввод
        }
    }, 1000);

    // Блокируем ввод времени во время работы таймера
    timerInput.setAttribute('disabled', true);
});


        // Обработчик кнопки Reset Timer
        document.getElementById('resetTimerButton').addEventListener('click', function () {
            const timerInput = document.getElementById('timerInput');
            const timerDisplay = document.getElementById('timerDisplay');

            // Сбрасываем таймер
            clearInterval(restTimerInterval);
            restTimerInterval = null;
            remainingTime = 0;

            // Сбрасываем отображение
            timerInput.removeAttribute('disabled'); // Разблокируем ввод времени
            timerInput.value = ''; // Очищаем поле ввода
            timerDisplay.textContent = ''; // Очищаем отображение
        });


    </script>
  
</body>
</html>
