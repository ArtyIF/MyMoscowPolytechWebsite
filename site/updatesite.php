<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Обновление сайта с GitHub</title>
</head>
<body>
    <h1>Обновление сайта с GitHub</h1>
    <h2>Вывод команды</h2>
    <pre><samp><?php
$gitOutput = `git pull --verbose`;
if ($gitOutput != NULL) {
    echo $gitOutput;
} else {
    echo "Произошла ошибка или программа ничего не вывела";
}
?></samp></pre>
</body>
</html>