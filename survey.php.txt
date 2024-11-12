<?php
session_start();

// Ім'я для відображення
$name = "Vladyslav Plakhotniuk";

// Функція для завантаження даних
function loadSurveys() {
    $dataFile = 'surveys.json';
    if (!file_exists($dataFile)) {
        file_put_contents($dataFile, json_encode([]));
    }
    return json_decode(file_get_contents($dataFile), true);
}

// Функція для збереження даних
function saveSurveys($surveys) {
    file_put_contents('surveys.json', json_encode($surveys));
}

// Завантаження всіх опитувань
$surveys = loadSurveys();

// Створення нового опитування
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_survey'])) {
    $title = $_POST['title'];
    $options = array_filter(explode("\n", trim($_POST['options'])));
    
    $newSurvey = [
        'id' => uniqid(),
        'title' => $title,
        'options' => array_fill_keys($options, 0),
    ];
    
    $surveys[] = $newSurvey;
    saveSurveys($surveys);
}

// Обробка відповіді на опитування
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
    $surveyId = $_POST['survey_id'];
    $selectedOption = $_POST['option'];

    foreach ($surveys as &$survey) {
        if ($survey['id'] === $surveyId && isset($survey['options'][$selectedOption])) {
            $survey['options'][$selectedOption]++;
        }
    }
    saveSurveys($surveys);
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Опитування - <?= htmlspecialchars($name) ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; }
        .survey { margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Система онлайн-опитувань - <?= htmlspecialchars($name) ?></h1>

        <h2>Створити нове опитування</h2>
        <form method="POST">
            <label>Назва опитування:</label><br>
            <input type="text" name="title" required><br><br>
            <label>Варіанти відповідей (кожен з нового рядка):</label><br>
            <textarea name="options" rows="4" required></textarea><br><br>
            <button type="submit" name="create_survey">Створити опитування</button>
        </form>

        <h2>Список опитувань</h2>
        <?php foreach ($surveys as $survey): ?>
            <div class="survey">
                <h3><?= htmlspecialchars($survey['title']) ?></h3>
                <form method="POST">
                    <input type="hidden" name="survey_id" value="<?= $survey['id'] ?>">
                    <?php foreach ($survey['options'] as $option => $votes): ?>
                        <label>
                            <input type="radio" name="option" value="<?= htmlspecialchars($option) ?>" required>
                            <?= htmlspecialchars($option) ?> (<?= $votes ?> голосів)
                        </label><br>
                    <?php endforeach; ?>
                    <button type="submit" name="vote">Проголосувати</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
