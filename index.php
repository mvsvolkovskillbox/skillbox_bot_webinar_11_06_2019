<?php

require "vendor/autoload.php";

$answer = '';
$html = '';

if (isset($_GET['question'])) {

    $question = mb_strtolower($_GET['question']);
    $answers = [
        [
            'searchPart' => ['var', 'переменн'],
            'files' => ['answers/variables.php'],

        ],


//    'var'      => ['answers/variables.php'],
//    'переменн' => ['answers/variables.php'],
        'услови'   => ['answers/if.php'],
        'цикл'     => ['answers/for.php', 'answers/while.php', 'answers/foreach.php'],
        'while'    => ['answers/while.php'],
        'foreach'  => ['answers/foreach.php'],
    ];

    foreach ($answers as $data) {
        foreach ($data['searchPart'] as $searchPart) {
            if (strstr($question, $searchPart)) {
                foreach ($data['files'] as $file) {
                    $answer .= file_get_contents($file) . PHP_EOL . PHP_EOL;
                }
                break;
            }
        }
    }

    foreach ($answers as $searchPart => $files) {
        if (strstr($question, $searchPart)) {
            foreach ($files as $file) {
                $answer .= file_get_contents($file) . PHP_EOL . PHP_EOL;
            }
        }
    }

    if (empty($answer)) {
        $dom = new \PHPHtmlParser\Dom;
        $dom->load("https://www.php.net/manual-lookup.php?pattern=$question&scope=$question");
        $html = $dom->find('#layout-content div')[1]->outerHtml;
    }
}

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Робот-помощник</title>
</head>
<body>
    <?php if (isset($question)) {?>
        <p>Ваш вопрос: <b><?=$question?></b></p>
    <?php }?>

    <?php if (! empty($answer)) {?>
        <pre><?php highlight_string($answer)?></pre>
    <?php } ?>

    <?php if (! empty($html)) {?>
        <h3>Я ответ не знаю, но на php.net я нашел это:</h3>
        <div><?=$html?></div>
    <?php }?>

    <hr>
    <h1>Спроси у меня - я тебе подскажу</h1>

    <form action="" method="get">
        <input placeholder="Введите ваш вопрос" name="question">
        <button type="submit">Спросить</button>
    </form>

</body>
</html>