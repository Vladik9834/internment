<?php
$dataFile = 'data.json';
$data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : ['polls' => []];

if (isset($_POST['poll_id'], $_POST['option'])) {
    $pollId = $_POST['poll_id'];
    $optionId = $_POST['option'];

    if (isset($data['polls'][$pollId]['options'][$optionId])) {
        $data['polls'][$pollId]['options'][$optionId]['votes']++;
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
        echo "Дякуємо за ваш голос! <a href='index.php'>Повернутися</a>";
    } else {
        echo "Неправильний варіант відповіді.";
    }
} else {
    echo "Будь ласка, виберіть відповідь.";
}
?>
