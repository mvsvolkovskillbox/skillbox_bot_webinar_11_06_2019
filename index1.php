<?php
require "vendor/autoload.php";

$question = '';
$answer = '';

if (! empty($_POST['ask'])) {

    $question = $_POST['question'];

    $answers = [
        'условие'  => ['/answers/if.php'],
        'if'       => ['/answers/if.php'],
        'цикл'     => [
            '/answers/for.php',
            '/answers/foreach.php',
        ],

        'for'      => ['/answers/for.php'],
        'переменн' => ['/answers/var.php'],
        'array'    => ['/answers/array.php'],

        'суперглобальн' => ['/answers/super_globals.php'],
        'post'          => ['/answers/super_globals.php'],
        'get'           => ['/answers/super_globals.php'],
    ];

    foreach ($answers as $key => $files) {
        if (strstr($question, $key)) {
            foreach ($files as $value) {
                $answer .= file_get_contents(__DIR__ . $value);
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
    <meta charset="utf-8">
    <title>Привет я бот помощник</title>
</head>
<body>
    <div>
        <?php if (! empty($question)) { ?>
            <p>Ваш вопрос: <b><?=$question?></b></p>
        <?php } ?>
        <?php if (! empty($answer)) { ?>
            <hr>
            <?php highlight_string($answer)?>
        <?php } ?>
        <?php if (! empty($html)) { ?>
            <hr>
            <h2>У меня нет информации об этом, но на php.net вот что я нашел</h2>
            <?= $html?>
        <?php } ?>
    </div>
    <hr>
    <div>
        <h2>Спроси у меня - я тебе подскажу</h2>
        <form method="post" action="/">
            <input name="question" value="" placeholder="Введите ваш вопрос">
            <button type="submit" name="ask" value="ask">Спросить</button>
        </form>
    </div>
</body>
</html>