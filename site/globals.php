<?php
$disciplineList = [
    "poya" => "Проблемно-ориентированные языки",
    "vss" => "Вычислительные системы и сети",
    "webapp" => "Разработка веб-приложений",
    // при добавлении новых дисциплин:
    // "название папки дисциплины" => "человеко-читабельное название дисциплины",
];

function get_discipline_human_name(string $disciplineID)
{
    global $disciplineList;
    if (array_key_exists($disciplineID, $disciplineList)) {
        return $disciplineList[$disciplineID];
    } else {
        return "ОШИБКА: неизвестный ID дисциплины: " . $disciplineID;
    }
}

class LabWork {
    public int $labWorkID;
    public array $childSectionIDs;
    
    function __construct($labWorkID) {
        $this->labWorkID = $labWorkID;
        $this->childSectionIDs = [];
    }

    function add_section($sectionName) {
        $sectionID = (int)rtrim($sectionName, ".html");
        array_push($this->childSectionIDs, $sectionID);
    }

    function sort_sections() {
        sort($this->childSectionIDs);
    }
}

class Discipline {
    public string $disciplineID;
    public string $disciplineHumanName;
    public array $childLabWorks;
    
    function __construct($disciplineID, $disciplineHumanName) {
        $this->disciplineID = $disciplineID;
        $this->disciplineHumanName = $disciplineHumanName;
        $this->childLabWorks = [];
    }

    function add_lab_work($labWorkName) {
        $labWorkID = (int)substr($labWorkName, 2);
        $labWork = new LabWork($labWorkID);
        array_push($this->childLabWorks, $labWork);
        return $labWork;
    }

    function lab_work_compare_func(LabWork $a, LabWork $b) {
        return $a->labWorkID - $b->labWorkID;
    }

    function sort_lab_works() {
        usort($this->childLabWorks, ["Discipline", "lab_work_compare_func"]);
    }
}

function get_all_lab_works() {
    $disciplines = [];

    chdir("iframes");
    $disciplineDirs = glob("*" , GLOB_ONLYDIR);
    foreach ($disciplineDirs as $disciplineDir) {
        $discipline = new Discipline($disciplineDir, get_discipline_human_name($disciplineDir));
        array_push($disciplines, $discipline);
        
        chdir($disciplineDir);
        $labWorkDirs = glob("*" , GLOB_ONLYDIR);
        foreach ($labWorkDirs as $labWorkDir) {
            $labWork = $discipline->add_lab_work($labWorkDir);

            chdir($labWorkDir);
            $sectionFiles = glob("*.html");
            foreach ($sectionFiles as $sectionFile) {
                $labWork->add_section($sectionFile);
            }
            $labWork->sort_sections();
            chdir("..");
        }
        $discipline->sort_lab_works();
        chdir("..");
    }
    chdir("..");

    return $disciplines;
}
?>