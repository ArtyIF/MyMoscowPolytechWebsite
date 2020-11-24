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
$gitOutput = [];
exec('git pull', $gitOutput);
foreach ($gitOutput as $gitOutputLine) {
    echo $gitOutputLine . "\n";
}
?></samp></pre>
</body>
</html>