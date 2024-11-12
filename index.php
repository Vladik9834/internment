<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Опитування - Vladyslav Plakhotniuk</title>
</head>
<body>
    <h1>Опитування - Vladyslav Plakhotniuk</h1>
    <?php
    // Завантаження даних
    $dataFile = 'data.json';
    $data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : ['polls' => []];

    // Додавання нового опитування
    if (isset($_POST['new_poll'])) {
        $question = $_POST['question'];
        $options = array_filter(array_map('trim', $_POST['options']));

        if ($question && count($options) > 1) {
            $poll = [
                'question' => $question,
                'options' => array_map(function($option) {
                    return ['text' => $option, 'votes' => 0];
                }, $options)
            ];
            $data['polls'][] = $poll;
            file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
            echo "<p>Опитування створено!</p>";
        } else {
            echo "<p>Заповніть питання та хоча б два варіанти відповідей.</p>";
        }
    }

    // Відображення форми для створення опитування
    ?>
    <h2>Створити опитування</h2>
    <form method="post">
        <label for="question">Питання:</label><br>
        <input type="text" name="question" required><br><br>

        <label>Варіанти відповідей (введіть щонайменше 2):</label><br>
        <input type="text" name="options[]" required><br>
        <input type="text" name="options[]" required><br>
        <button type="button" onclick="addOption()">Додати варіант</button><br><br>

        <button type="submit" name="new_poll">Створити опитування</button>
    </form>

    <script>
        function addOption() {
            const input = document.createElement("input");
            input.type = "text";
            input.name = "options[]";
            document.querySelector("form").insertBefore(input, document.querySelector("button[type=button]"));
        }
    </script>

    <h2>Доступні опитування</h2>
    <?php
    // Відображення існуючих опитувань
    foreach ($data['polls'] as $index => $poll) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($poll['question']) . "</h3>";
        echo "<form action='submit.php' method='post'>";
        echo "<input type='hidden' name='poll_id' value='$index'>";
        foreach ($poll['options'] as $i => $option) {
            echo "<label>";
            echo "<input type='radio' name='option' value='$i' required> " . htmlspecialchars($option['text']);
            echo "</label><br>";
        }
        echo "<button type='submit'>Голосувати</button>";
        echo "</form>";
        echo "<a href='results.php?poll_id=$index'>Переглянути результати</a>";
        echo "</div><hr>";
    }
    ?>
</body>
</html>
