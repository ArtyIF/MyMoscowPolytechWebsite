<?php
$discdict = [
    "poya" => "Проблемно-ориентированные языки",
    "vss" => "Вычислительные системы и сети",
    "webapp" => "Разработка веб-приложений",
    // при добавлении новых дисциплин:
    // "название папки дисциплины" => "человеко-читабельное название дисциплины",
];

function get_disc_human_name(string $discid)
{
    global $discdict;
    if (array_key_exists($discid, $discdict)) {
        return $discdict[$discid];
    } else {
        return "ОШИБКА: неизвестный ID дисциплины: " . $discid;
    }
}
?>