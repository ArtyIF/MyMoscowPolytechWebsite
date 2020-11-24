<?php
$disciplineList = [
    "poya" => "Проблемно-ориентированные языки",
    "vss" => "Вычислительные системы и сети",
    "webapp" => "Разработка веб-приложений",
    // при добавлении новых дисциплин:
    // "название папки дисциплины" => "человеко-читабельное название дисциплины",
];

function get_disc_human_name(string $disciplineID) {
    global $disciplineList;
    if (array_key_exists($disciplineID, $disciplineList)) {
        return $disciplineList[$disciplineID];
    } else {
        return "ОШИБКА: неизвестный ID дисциплины: " . $disciplineID;
    }
}

function get_lab_works_count(string $disciplineID) : int {
    return count(glob("iframes/" . $disciplineID . "*" , GLOB_ONLYDIR));
}

function get_sections_count(string $disciplineID, int $labWorkID) : int {
    return count(glob("iframes/" . $disciplineID . "/lr" . $labWorkID . "/*" , GLOB_ONLYDIR));
}
?>