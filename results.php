<?php
$dataFile = 'data.json';
$data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : ['polls' => []];

if (isset($_GET['poll_id'])) {
    $pollId = $_GET['poll_id'];

    if (isset($data['polls'][$pollId])) {
        $poll = $data['polls'][$pollId];
        echo "<h1>Результати опитування</h1>";
        echo "<h2>" . htmlspecialchars($poll['question']) . "</h2>";
        echo "<ul>";
        foreach ($poll['options'] as $option) {
            echo "<li>" . htmlspecialchars($option['text']) . ": " . $option['votes'] . " голосів</li>";
        }
        echo "</ul>";
        echo "<a href='index.php'>Повернутися до опитувань</a>";
    } else {
        echo "Опитування не знайдено.";
    }
} else {
    echo "Неправильний запит.";
}
?>
